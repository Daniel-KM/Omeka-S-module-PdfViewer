<?php
namespace PdfViewer\Media\FileRenderer;

use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\FileRenderer\RendererInterface;
use Zend\View\Renderer\PhpRenderer;

class Pdf implements RendererInterface
{
    /**
     * The default partial view script.
     */
    const PARTIAL_NAME = 'common/pdf-viewer';

    /**
     * @var array
     */
    protected $defaultOptions = [
        'attributes' => 'allowfullscreen="allowfullscreen" style="height: 600px; height: 70vh;"',
        'template' => self::PARTIAL_NAME,
    ];

    /**
     * Render a pdf via the Mozilla library pdf.js.
     *
     * @param PhpRenderer $view,
     * @param MediaRepresentation $media
     * @param array $options These options are managed for sites:
     *   - template: the partial to use
     *   - attributes: set the attributes to add
     * @return string
     */
    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        if ($view->status()->isSiteRequest()) {
            $siteSetting = $view->plugin('siteSetting');
            $template = isset($options['template'])
                ? $options['template']
                : $siteSetting('pdfviewer_template', $this->defaultOptions['template']);
            $options['attributes'] = isset($options['attributes'])
                ? $options['attributes']
                : $this->defaultOptions['attributes'];
        } else {
            $template = $this->defaultOptions['template'];
            $options['attributes'] = $this->defaultOptions['attributes'];
        }

        unset($options['template']);
        return $view->partial($template, [
            'media' => $media,
            'options' => $options,
        ]);
    }
}
