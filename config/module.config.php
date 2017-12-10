<?php
namespace DocumentViewer;

return [
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'file_renderers' => [
        'invokables' => [
            'pdfViewer' => Media\FileRenderer\Pdf::class,
        ],
        'aliases' => [
            'application/pdf' => 'pdfViewer',
            'pdf' => 'pdfViewer',
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\ConfigForm::class => Service\Form\ConfigFormFactory::class,
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'documentviewer' => [
        'config' => [
            'documentviewer_pdf_mode' => 'object',
            'documentviewer_pdf_style' => 'height: 600px; height: 70vh;',
        ],
        'site_settings' => [
            'documentviewer_pdf_mode' => 'object',
            'documentviewer_pdf_style' => 'height: 600px; height: 70vh;',
        ],
    ],
];
