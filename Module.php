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
    protected $settings = [
        'documentviewer_pdf_mode' => 'object',
        'documentviewer_pdf_style' => 'height: 600px;',
    ];

    protected $siteSettings = [
        'documentviewer_pdf_mode' => 'object',
        'documentviewer_pdf_style' => 'height: 600px;',
    ];

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function install(ServiceLocatorInterface $serviceLocator)
    {
        $settings = $serviceLocator->get('Omeka\Settings');
        $siteSettings = $serviceLocator->get('Omeka\Settings\Site');
        $api = $serviceLocator->get('Omeka\ApiManager');
        $t = $serviceLocator->get('MvcTranslator');

        $js = __DIR__ . '/asset/vendor/pdfjs/build/pdf.js';
        if (!file_exists($js)) {
            throw new ModuleCannotInstallException(
                $t->translate('The Mozilla pdf.js library should be installed.') // @translate
                    . ' ' . $t->translate('See module’s installation documentation.')); // @translate
        }

        foreach ($this->settings as $name => $value) {
            $settings->set($name, $value);
        }

        $sites = $api->search('sites')->getContent();
        foreach ($sites as $site) {
            $siteSettings->setTargetId($site->id());
            foreach ($this->siteSettings as $name => $value) {
                $siteSettings->set($name, $value);
            }
        }
    }

    public function uninstall(ServiceLocatorInterface $serviceLocator)
    {
        $settings = $serviceLocator->get('Omeka\Settings');
        $siteSettings = $serviceLocator->get('Omeka\Settings\Site');
        $api = $serviceLocator->get('Omeka\ApiManager');

        foreach ($this->settings as $name => $value) {
            $settings->delete($name);
        }

        $sites = $api->search('sites')->getContent();
        foreach ($sites as $site) {
            $siteSettings->setTargetId($site->id());
            foreach ($this->siteSettings as $name => $value) {
                $siteSettings->delete($name);
            }
        }
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        $sharedEventManager->attach(
            'Omeka\Form\SiteSettingsForm',
            'form.add_elements',
            [$this, 'addSiteSettingsFormElements']
        );
    }

    public function getConfigForm(PhpRenderer $renderer)
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        $formElementManager = $services->get('FormElementManager');

        $data = [];
        foreach ($this->settings as $name => $value) {
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
        $settings = $this->getServiceLocator()->get('Omeka\Settings');

        $params = $controller->getRequest()->getPost();
        foreach ($params as $name => $value) {
            if (isset($this->settings[$name])) {
                $settings->set($name, $value);
            }
        }
    }

    public function addSiteSettingsFormElements(Event $event)
    {
        $services = $this->getServiceLocator();
        $siteSettings = $services->get('Omeka\Settings\Site');
        $form = $event->getTarget();

        $fieldset = new Fieldset('pdf_viewer');
        $fieldset->setLabel('Document Viewer');

        $valueOptions = [
            'inline' => 'Inline (easy customizable)', // @translate
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
                    $this->siteSettings['documentviewer_pdf_mode']
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
                    $this->siteSettings['documentviewer_pdf_style']
                ),
            ],
        ]);

        $form->add($fieldset);
    }
}
