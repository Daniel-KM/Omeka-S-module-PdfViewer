<?php
namespace DocumentViewer\Form;

use Zend\Form\Form;

class ConfigForm extends Form
{
    public function init()
    {
        $valueOptions = [
            'inline' => 'Inline (easily customizable)', // @translate
            'object' => 'Object', // @translate
            'embed' => 'Embed', // @translate
            'iframe' => 'Inline frame', // @translate
            'object_iframe' => 'Object + iframe (max compatibility)', // @translate
        ];
        $this->add([
            'name' => 'documentviewer_pdf_mode',
            'type' => 'Select',
            'options' => [
                'label' => 'Integration mode', // @translate
                'info' => 'According to the needed compatibility level, the pdf viewer can be embedded in multiple ways.' // @translate
                    . ' '  . 'These options are used in the admin board.', // @translate
                'value_options' => $valueOptions,
            ],
            'attributes' => [
                'required' => 'true',
            ],
        ]);

        $this->add([
            'name' => 'documentviewer_pdf_style',
            'type' => 'Text',
            'options' => [
                'label' => 'Inline style', // @translate
                'info' => 'If any, this style will be added to the main div of the pdf.' // @translate
                    . ' ' . 'The height may be required.', // @translate
            ],
        ]);
    }
}
