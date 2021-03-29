<?php declare(strict_types=1);

// Install and update the original sources.
$dirname = dirname(__DIR__, 2) . '/';

@unlink($dirname . 'asset/vendor/pdf.js/web/compressed.tracemonkey-pldi-09.pdf');

// From generic version.

echo 'Updating asset/vendor/pdf.js/web/viewer.css' . PHP_EOL;
$replacements = [
    'html {' => '.pdfjs-html {',
    'body {' => '.pdfjs {',
    'body,'  => '.pdfjs,',
];
$filepath = $dirname . 'asset/vendor/pdf.js/web/viewer.css';
$content = file_get_contents($filepath);
$content = str_replace(array_keys($replacements), array_values($replacements), $content);
$content = preg_replace('~^(\s*)(\*|button|input|select)(,| \{)$~m', '$1.pdfjs $2$3', $content);
$content .= "\n/*# sourceMappingURL=viewer.css.map */\n";
file_put_contents($filepath, $content);

echo 'Updating asset/vendor/pdf.js/web/viewer.js' . PHP_EOL;
$replacements = [
    "value: 'compressed.tracemonkey-pldi-09.pdf'," => 'value: documentUrl,',
    'value: "compressed.tracemonkey-pldi-09.pdf",' => 'value: documentUrl,',
    "value: '../build/pdf.worker.js'," => "value: '',",
    'value: "../build/pdf.worker.js",' => "value: '',",
];
$filepath = $dirname . 'asset/vendor/pdf.js/web/viewer.js';
$content = file_get_contents($filepath);
$content = str_replace(array_keys($replacements), array_values($replacements), $content);
file_put_contents($filepath, $content);
