<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/controllers')
    ->in(__DIR__ . '/models')
    ->in(__DIR__ . '/services')
    ->in(__DIR__ . '/commands')
    ->name('*.php')
    ->ignoreVCS(true)
    ->ignoreDotFiles(true);

return new PhpCsFixer\Config()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'align_single_space_minimal'],
        'no_unused_imports' => true,
        'no_trailing_whitespace' => true,
        'single_quote' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'blank_line_before_statement' => ['statements' => ['return']],
        'concat_space' => ['spacing' => 'one'],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'fully_qualified_strict_types' => true,
        'final_class' => true,
        'final_internal_class' => true,
        'self_accessor' => true,
    ])
    ->setFinder($finder);


