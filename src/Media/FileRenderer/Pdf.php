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
        'attributes' => 'allowfullscreen="allowfullscreen"',
        'style' => 'height: 600px; height: 70vh',
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

        $isAdmin = $view->params()->fromRoute('__ADMIN__');
        if ($isAdmin) {
            $mode = $view->setting('documentviewer_pdf_mode', $this->defaultOptions['mode']);
            $attributes = $this->defaultOptions['attributes'];
            $style = $view->setting('documentviewer_pdf_style', $this->defaultOptions['style']);
        } else {
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

        switch ($mode) {
            case 'inline':
                return $view->partial('common/pdf-viewer-inline', [
                    'media' => $media,
                    'attributes' => $attributes,
                    'style' => $style,
                ]);

            case 'object':
                $html = '<object height="100%%" width="100%%" %1$s%2$s data="%3$s" type="application/pdf">%4$s</object>';
                break;

            case 'embed':
                $html = '<embed height="100%%" width="100%%" %1$s%2$s src="%3$s" type="application/pdf" />';
                break;

            case 'iframe':
                $html = '<iframe height="100%%" width="100%%" %1$s%2$s src="%3$s">%4$s</iframe>';
                break;

            case 'object_iframe':
                $html = '<object height="100%%" width="100%%" %1$s%2$s data="%3$s" type="application/pdf">'
                    . '<iframe height="100%%" width="100%%" %1$s%2$s src="%3$s">%4$s</iframe>'
                    . '</object>';
                break;

            case 'custom':
                return $view->partial('common/document-viewer', [
                    'media' => $media,
                    'attributes' => $attributes,
                    'style' => $style,
                ]);
                break;

            default:
                return new Message('The mode "%s" is not managed by the pdf viewer.', $mode); // @translate
        }
        return vsprintf($html, [$attributes, $style ? ' style="' . $style . '"' : '', $media->originalUrl(), $this->fallback($media)]);
    }

    protected function fallback($media)
    {
        $view = $this->getView();
        $text = $view->escapeHtml($view->translate('This browser does not support PDF.'))
            . ' ' . sprintf($view->translate('You may %sdownload it%s to view it offline.'), // @translate
                '<a href="' . $media->originalUrl() . '">', '</a>');
        $html = '<p>' . $text . '</p>'
            . '<img src="' . $media->thumbnailUrl('large') . '" height="600px" />';
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
