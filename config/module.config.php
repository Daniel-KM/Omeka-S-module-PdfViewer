<?php
namespace DocumentViewer;

return [
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'file_renderers' => [
        'factories' => [
            'pdfViewer' => Service\Media\FileRenderer\PdfFactory::class,
        ],
        'aliases' => [
            'application/pdf' => 'pdfViewer',
            'pdf' => 'pdfViewer',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'DocumentViewer\Form\ConfigForm' => Form\ConfigForm::class,
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
        'settings' => [
            'documentviewer_pdf_mode' => 'object',
            'documentviewer_pdf_style' => 'height: 600px; height: 70vh;',
        ],
        'site_settings' => [
            'documentviewer_pdf_mode' => 'object',
            'documentviewer_pdf_style' => 'height: 600px; height: 70vh;',
        ],
    ],
];
