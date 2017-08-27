<?php

namespace DocumentViewer\Service\Media\FileRenderer;

use Interop\Container\ContainerInterface;
use Omeka\Module\Manager as ModuleManager;
use DocumentViewer\Media\FileRenderer\Pdf;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Service factory for the pdf file renderer.
 */
class PdfFactory implements FactoryInterface
{
    /**
     * Create and return the Pdf file renderer.
     *
     * @return Pdf
     */
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $currentTheme = $serviceLocator->get('Omeka\Site\ThemeManager')
            ->getCurrentTheme();
        return new Pdf($currentTheme);
    }
}
