<?php
namespace PdfViewer\Form;

use Zend\Form\Element;
use Zend\Form\Fieldset;

class SiteSettingsFieldset extends Fieldset
{
    protected $label = 'Pdf Viewer'; // @translate

    public function init()
    {
        $this
            ->add([
                'name' => 'pdfviewer_template',
                'type' => Element\Select::class,
                'options' => [
                    'label' => 'Integration mode', // @translate
                    'info' => 'According to the needed compatibility level, the pdf viewer can be embedded in multiple ways. These options are used in the admin board.', // @translate
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
