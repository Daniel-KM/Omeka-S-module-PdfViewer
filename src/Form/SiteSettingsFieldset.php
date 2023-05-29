<?php declare(strict_types=1);
namespace PdfViewer\Form;

use Laminas\Form\Element;
use Laminas\Form\Fieldset;

class SiteSettingsFieldset extends Fieldset
{
    protected $label = 'Pdf Viewer'; // @translate

    protected $elementGroups = [
        // "Player" is used instead of viewer, because "viewer" is used for a site
        // user role and cannot be translated differently (no context).
        // Player is polysemic too anyway, but less used and more adapted for
        // non-image viewers.
        'player' => 'Players', // @translate
    ];

    public function init(): void
    {
        $this
            ->setAttribute('id', 'pdf-viewer')
            ->setOption('element_groups', $this->elementGroups)
            ->add([
                'name' => 'pdfviewer_template',
                'type' => Element\Select::class,
                'options' => [
                    'element_group' => 'player',
                    'label' => 'PDF viewer Integration mode', // @translate
                    'info' => 'According to the needed compatibility level, the pdf viewer can be embedded in multiple ways.', // @translate
                    'value_options' => [
                        'common/pdf-viewer' => 'Iframe (most common)', // @translate
                        'common/pdf-viewer-inline' => 'Inline (easily customizable)', // @translate
                        'common/pdf-viewer-object' => 'Object', // @translate
                        'common/pdf-viewer-embed' => 'Embed', // @translate
                        'common/pdf-viewer-object_iframe' => 'Object + iframe (max compatibility)', // @translate
                        'common/pdf-viewer-custom' => 'Custom (via theme)', // @translate
                    ],
                ],
                'attributes' => [
                    'id' => 'pdfviewer_template',
                ],
            ])
        ;
    }
}
