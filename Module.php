<?php

/*
 * Copyright 2017 Daniel Berthereau
 *
 * This software is governed by the CeCILL license under French law and abiding
 * by the rules of distribution of free software. You can use, modify and/or
 * redistribute the software under the terms of the CeCILL license as circulated
 * by CEA, CNRS and INRIA at the following URL "http://www.cecill.info".
 *
 * As a counterpart to the access to the source code and rights to copy, modify
 * and redistribute granted by the license, users are provided only with a
 * limited warranty and the software’s author, the holder of the economic
 * rights, and the successive licensors have only limited liability.
 *
 * In this respect, the user’s attention is drawn to the risks associated with
 * loading, using, modifying and/or developing or reproducing the software by
 * the user in light of its specific status of free software, that may mean that
 * it is complicated to manipulate, and that also therefore means that it is
 * reserved for developers and experienced professionals having in-depth
 * computer knowledge. Users are therefore encouraged to load and test the
 * software’s suitability as regards their requirements in conditions enabling
 * the security of their systems and/or data to be ensured and, more generally,
 * to use and operate it in the same conditions as regards security.
 *
 * The fact that you are presently reading this means that you have had
 * knowledge of the CeCILL license and that you accept its terms.
 */

namespace DocumentViewer;

use Omeka\Module\AbstractModule;
use Omeka\Module\Exception\ModuleCannotInstallException;
use DocumentViewer\Form\Config as ConfigForm;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\Form\Fieldset;
use Zend\Mvc\Controller\AbstractController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\PhpRenderer;

class Module extends AbstractModule
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function install(ServiceLocatorInterface $serviceLocator)
    {
        $api = $serviceLocator->get('Omeka\ApiManager');
        $t = $serviceLocator->get('MvcTranslator');

        $js = __DIR__ . '/asset/vendor/pdfjs/build/pdf.js';
        if (!file_exists($js)) {
            throw new ModuleCannotInstallException(
                $t->translate('The Mozilla pdf.js library should be installed.') // @translate
                    . ' ' . $t->translate('See module’s installation documentation.')); // @translate
        }

        $this->manageSettings($serviceLocator->get('Omeka\Settings'), 'install');
        $this->manageSiteSettings($serviceLocator, 'install');
    }

    public function uninstall(ServiceLocatorInterface $serviceLocator)
    {
        $this->manageSettings($serviceLocator->get('Omeka\Settings'), 'uninstall');
        $this->manageSiteSettings($serviceLocator, 'uninstall');
    }

    public function upgrade($oldVersion, $newVersion, ServiceLocatorInterface $serviceLocator)
    {
        if (version_compare($oldVersion, '3.0.1', '<')) {
            $settings = $serviceLocator->get('Omeka\Settings');
            $config = include __DIR__ . '/config/module.config.php';
            foreach ($config[strtolower(__NAMESPACE__)]['settings'] as $name => $value) {
                $oldName = str_replace('documentviewer_', 'document_viewer_', $name);
                $settings->set($name, $settings->get($oldName, $value));
                $settings->delete($oldName);
            }

            $settings = $serviceLocator->get('Omeka\Settings\Site');
            $api = $serviceLocator->get('Omeka\ApiManager');
            $sites = $api->search('sites')->getContent();
            foreach ($sites as $site) {
                $settings->setTargetId($site->id());
                foreach ($config[strtolower(__NAMESPACE__)]['site_settings'] as $name => $value) {
                    $oldName = str_replace('documentviewer_', 'document_viewer_', $name);
                    $settings->set($name, $settings->get($oldName, $value));
                    $settings->delete($oldName);
                }
            }
        }
    }

    protected function manageSettings($settings, $process, $key = 'settings')
    {
        $config = require __DIR__ . '/config/module.config.php';
        $defaultSettings = $config[strtolower(__NAMESPACE__)][$key];
        foreach ($defaultSettings as $name => $value) {
            switch ($process) {
                case 'install':
                    $settings->set($name, $value);
                    break;
                case 'uninstall':
                    $settings->delete($name);
                    break;
            }
        }
    }

    protected function manageSiteSettings(ServiceLocatorInterface $serviceLocator, $process)
    {
        $siteSettings = $serviceLocator->get('Omeka\Settings\Site');
        $api = $serviceLocator->get('Omeka\ApiManager');
        $sites = $api->search('sites')->getContent();
        foreach ($sites as $site) {
            $siteSettings->setTargetId($site->id());
            $this->manageSettings($siteSettings, $process, 'site_settings');
        }
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        $sharedEventManager->attach(
            \Omeka\Form\SiteSettingsForm::class,
            'form.add_elements',
            [$this, 'addSiteSettingsFormElements']
        );
        $sharedEventManager->attach(
            \Omeka\Form\SiteSettingsForm::class,
            'form.add_input_filters',
            [$this, 'addSiteSettingsFilters']
        );
    }

    public function getConfigForm(PhpRenderer $renderer)
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');
        $settings = $services->get('Omeka\Settings');
        $formElementManager = $services->get('FormElementManager');

        $data = [];
        $defaultSettings = $config[strtolower(__NAMESPACE__)]['settings'];
        foreach ($defaultSettings as $name => $value) {
            $data[$name] = $settings->get($name);
        }

        $form = $formElementManager->get(ConfigForm::class);
        $form->init();
        $form->setData($data);
        $html = $renderer->formCollection($form);
        return $html;
    }

    public function handleConfigForm(AbstractController $controller)
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');
        $settings = $services->get('Omeka\Settings');

        $params = $controller->getRequest()->getPost();

        $form = $this->getServiceLocator()->get('FormElementManager')
            ->get(ConfigForm::class);
        $form->init();
        $form->setData($params);
        if (!$form->isValid()) {
            $controller->messenger()->addErrors($form->getMessages());
            return false;
        }

        $defaultSettings = $config[strtolower(__NAMESPACE__)]['settings'];
        foreach ($params as $name => $value) {
            if (isset($defaultSettings[$name])) {
                $settings->set($name, $value);
            }
        }
    }

    public function addSiteSettingsFormElements(Event $event)
    {
        $services = $this->getServiceLocator();
        $siteSettings = $services->get('Omeka\Settings\Site');
        $config = $services->get('Config');
        $form = $event->getTarget();

        $defaultSiteSettings = $config[strtolower(__NAMESPACE__)]['site_settings'];

        $fieldset = new Fieldset('document_viewer');
        $fieldset->setLabel('Document Viewer');

        $valueOptions = [
            'inline' => 'Inline (easily customizable)', // @translate
            'object' => 'Object', // @translate
            'embed' => 'Embed', // @translate
            'iframe' => 'Inline frame', // @translate
            'object_iframe' => 'Object + iframe (max compatibility)', // @translate
        ];
        $fieldset->add([
            'name' => 'documentviewer_pdf_mode',
            'type' => 'Select',
            'options' => [
                'label' => 'Integration mode', // @translate
                'info' => 'According to the needed compatibility level, the pdf viewer can be embedded in multiple ways.', // @translate
                'value_options' => $valueOptions,
            ],
            'attributes' => [
                'value' => $siteSettings->get(
                    'documentviewer_pdf_mode',
                    $defaultSiteSettings['documentviewer_pdf_mode']
                ),
                // 'required' => 'true',
            ],
        ]);

        $fieldset->add([
            'name' => 'documentviewer_pdf_style',
            'type' => 'Text',
            'options' => [
                'label' => 'Inline style', // @translate
                'info' => 'If any, this style will be added to the main div of the Document Viewer.' // @translate
                   . ' ' . 'The height may be required.', // @translate
            ],
            'attributes' => [
                'value' => $siteSettings->get(
                    'documentviewer_pdf_style',
                    $defaultSiteSettings['documentviewer_pdf_style']
                ),
            ],
        ]);

        $form->add($fieldset);
    }

    public function addSiteSettingsFilters(Event $event)
    {
        $inputFilter = $event->getParam('inputFilter');
        $inputFilter->get('document_viewer')->add([
            'name' => 'documentviewer_pdf_mode',
            'required' => true,
        ]);
    }
}
