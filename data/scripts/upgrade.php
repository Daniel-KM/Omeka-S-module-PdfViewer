<?php
namespace DocumentViewer;

/**
 * @var Module $this
 * @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
 * @var string $newVersion
 * @var string $oldVersion
 *
 * @var \Doctrine\DBAL\Connection $connection
 * @var \Doctrine\ORM\EntityManager $entityManager
 * @var \Omeka\Api\Manager $api
 */
$services = $serviceLocator;
$settings = $services->get('Omeka\Settings');
$config = require dirname(dirname(__DIR__)) . '/config/module.config.php';
$connection = $services->get('Omeka\Connection');
$entityManager = $services->get('Omeka\EntityManager');
$api = $services->get('Omeka\ApiManager');
$space = strtolower(__NAMESPACE__);

if (version_compare($oldVersion, '3.0.1', '<')) {
    foreach ($config[$space]['settings'] as $name => $value) {
        $oldName = str_replace('documentviewer_', 'document_viewer_', $name);
        $settings->set($name, $settings->get($oldName, $value));
        $settings->delete($oldName);
    }

    $settings = $serviceLocator->get('Omeka\Settings\Site');
    $sites = $api->search('sites')->getContent();
    foreach ($sites as $site) {
        $settings->setTargetId($site->id());
        foreach ($config[$space]['site_settings'] as $name => $value) {
            $oldName = str_replace('documentviewer_', 'document_viewer_', $name);
            $settings->set($name, $settings->get($oldName, $value));
            $settings->delete($oldName);
        }
    }
}
