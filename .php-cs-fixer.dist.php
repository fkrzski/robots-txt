<?php

$rules = [
    '@PSR2' => true,
    '@PHP81Migration' => true,
    'array_push' => true,
    'backtick_to_shell_exec' => true,
    'declare_strict_types' => true,
    'global_namespace_import' => [
        'import_classes' => true,
        'import_constants' => false,
        'import_functions' => false,
    ],
    'ordered_class_elements' => [
        'order' => [
            'use_trait',
            'case',
            'constant',
            'constant_public',
            'constant_protected',
            'constant_private',
            'property_public',
            'property_protected',
            'property_private',
            'construct',
            'destruct',
            'magic',
            'phpunit',
            'method_abstract',
            'method_public_static',
            'method_public',
            'method_protected_static',
            'method_protected',
            'method_private_static',
            'method_private',
        ],
        'sort_algorithm' => 'none',
    ],
    'ordered_interfaces' => true,
    'ordered_traits' => true,
    'protected_to_private' => true,
    'strict_comparison' => true,
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'braces' => true,
    'class_definition' => true,
    'concat_space' => [
        'spacing' => 'none',
    ],
    'fully_qualified_strict_types' => true,
    'function_declaration' => true,
    'indentation_type' => true,
    'line_ending' => true,
    'method_argument_space' => [
        'on_multiline' => 'ensure_fully_multiline',
    ],
    'no_break_comment' => true,
    'no_closing_tag' => true,
    'no_spaces_after_function_name' => true,
    'no_spaces_inside_parenthesis' => true,
    'no_trailing_whitespace' => true,
    'no_trailing_whitespace_in_comment' => true,
    'single_blank_line_at_eof' => true,
    'single_import_per_statement' => true,
    'single_line_after_imports' => true,
    'switch_case_semicolon_to_colon' => true,
    'switch_case_space' => true,
    'visibility_required' => true,
];

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules($rules)
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
