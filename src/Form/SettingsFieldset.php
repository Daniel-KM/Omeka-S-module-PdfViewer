<?php
namespace DocumentViewer\Form;

use Zend\Form\Element;
use Zend\Form\Fieldset;

class SettingsFieldset extends Fieldset
{
    public function init()
    {
        $this->setLabel('Document Viewer'); // @translate

        $valueOptions = [
            'inline' => 'Inline (easily customizable)', // @translate
            'object' => 'Object', // @translate
            'embed' => 'Embed', // @translate
            'iframe' => 'Iframe (most common)', // @translate
            'object_iframe' => 'Object + iframe (max compatibility)', // @translate
            'custom' => 'Custom (via the partial common/document-viewer)', // @translate
        ];

        $this->add([
            'name' => 'documentviewer_pdf_mode',
            'type' => Element\Select::class,
            'options' => [
                'label' => 'Integration mode', // @translate
                'info' => 'According to the needed compatibility level, the pdf viewer can be embedded in multiple ways. These options are used in the admin board.', // @translate
                'value_options' => $valueOptions,
            ],
            'attributes' => [
                'required' => 'true',
            ],
        ]);

        $this->add([
            'name' => 'documentviewer_pdf_style',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Inline style', // @translate
                'info' => 'If any, this style will be added to the main div of the pdf. The height may be required.', // @translate
            ],
        ]);
    }
}
