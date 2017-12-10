<?php
namespace DocumentViewer\Media\FileRenderer;

use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\FileRenderer\RendererInterface;
use Omeka\Stdlib\Message;
use Zend\View\Renderer\PhpRenderer;

class Pdf implements RendererInterface
{
    /**
     * These options are used only when the player is called outside of a site
     * or when the site settings are not set.
     *
     * @var array
     */
    protected $defaultOptions = [
        'mode' => 'inline',
        'attributes' => 'allowfullscreen="1"',
        'style' => 'height: 600px;',
    ];

    /**
     * @var PhpRenderer
     */
    protected $view;

    /**
     * Render a pdf via the Mozilla library pdf.js.
     *
     * @param PhpRenderer $view,
     * @param MediaRepresentation $media
     * @param array $options These options are managed for sites:
     *   - mode: set the rendering mode: "inline" (default), "object" (via the
     *   browser reader), "embed", "iframe", or "object_iframe" (for old
     *   compatibiliy).
     *   - attributes: set the attributes to add
     *   - style: set the inline style
     * @return string
     */
    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        $this->setView($view);

        $isSite = $view->params()->fromRoute('__SITE__');
        // For admin board.
        if (empty($isSite)) {
            $mode = $view->setting('documentviewer_pdf_mode', $this->defaultOptions['mode']);
            $attributes = $this->defaultOptions['attributes'];
            $style = $view->setting('documentviewer_pdf_style', $this->defaultOptions['style']);
        }
        // For sites.
        else {
            $mode = isset($options['mode'])
                ? $options['mode']
                : $view->siteSetting('documentviewer_pdf_mode', $this->defaultOptions['mode']);

            $attributes = isset($options['attributes'])
                ? $options['attributes']
                : $view->siteSetting('documentviewer_pdf_attributes', $this->defaultOptions['attributes']);

            $style = isset($options['style'])
                ? $options['style']
                : $view->siteSetting('documentviewer_pdf_style', $this->defaultOptions['style']);
        }

        if (!empty($style)) {
            $attributes .= ' style="' . $view->escapeHtml($style) . '"';
        }

        switch ($mode) {
            case 'inline':
                $values = [
                    'media' => $media,
                    'attributes' => $attributes,
                ];
                return $view->partial('common/pdf-viewer', $values);

            case 'object':
                $url = $view->escapeHtml($media->originalUrl());
                return '<object height="100%" width="100%" ' . $attributes . ' data="'. $url . '" type="application/pdf">'
                    . $this->fallback($media)
                    . '</object>';

            case 'embed':
                $url = $view->escapeHtml($media->originalUrl());
                return '<embed height="100%" width="100%" ' . $attributes . ' src="'. $url . '" type="application/pdf" />';

            case 'iframe':
                $url = $view->escapeHtml($media->originalUrl());
                return '<iframe height="100%" width="100%" ' . $attributes . ' src="' . $url . '">'
                    . $this->fallback($media)
                    . '</iframe>';

            case 'object_iframe':
                $url = $view->escapeHtml($media->originalUrl());
                return '<object height="100%" width="100%" ' . $attributes . ' data="'. $url . '" type="application/pdf">'
                    . '<iframe height="100%" width="100%" ' . $attributes . ' src="' . $url . '">'
                    . $this->fallback($media)
                    . '</iframe></object>';

            default:
                return new Message('The mode "%s" is not managed by the pdf viewer.', $mode); // @translate
        }
    }

    // universal viewer asset path
//

    protected function fallback($media)
    {
        $view = $this->getView();
        $text = $view->translate('This browser does not support PDF.')
            . ' ' . sprintf($view->translate('You may %sdownload it%s to view it offline.'), // @translate
                '<a href="' . $view->escapeHtml($media->originalUrl()) . '">', '</a>');
        $html = '<p>' . $text . '</p>'
            . '<img src="' . $view->escapeHtml($media->thumbnailUrl('large')) . '" height="600px" />';
        return $html;
    }

    /**
     * @param PhpRenderer $view
     */
    protected function setView(PhpRenderer $view)
    {
        $this->view = $view;
    }

    /**
     * @return PhpRenderer
     */
    protected function getView()
    {
        return $this->view;
    }
}
