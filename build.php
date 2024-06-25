<?php
// build.php
// Load the SCSS compiler
require 'vendor/autoload.php';

use ScssPhp\ScssPhp\Compiler;

// Define base directory for SCSS files
$scssBaseDir = 'private/scss/';

// Define paths to main SCSS and CSS files
$mainScssFilePath = $scssBaseDir . 'main.scss';
$cssFilePath = 'public/css/styles.css';

// Load main SCSS file
$mainScss = file_get_contents($mainScssFilePath);

// Compile SCSS to CSS
$compiler = new Compiler();
$compiler->setImportPaths($scssBaseDir); // Set import paths for SCSS files
$cssResult = $compiler->compileString($mainScss);

// Extract compiled CSS from CompilationResult object
$css = $cssResult->getCss();

// Save compiled CSS to file
file_put_contents($cssFilePath, $css);

echo 'SCSS compiled successfully!';
