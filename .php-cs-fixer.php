<?php

// https://mlocati.github.io/php-cs-fixer-configurator/

$rules = [
    '@PSR1' => true,
    '@PSR2' => true,

    'array_indentation' => false, // this causes trouble when chaining calls that are passed an array argument
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => ['default' => 'single_space'],
    'cast_spaces' => ['space' => 'single'],
    'class_attributes_separation' => ['elements' => ['const' => 'one', 'method' => 'one', 'property' => 'one']],
    'compact_nullable_typehint' => true,
    'concat_space' => ['spacing' => 'none'],
    'function_typehint_space' => true,
    'linebreak_after_opening_tag' => true,
    'list_syntax' => ['syntax' => 'short'],
    'lowercase_cast' => true,
    'lowercase_static_reference' => true,
    'magic_constant_casing' => true,
    'magic_method_casing' => true,
    'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
    'method_chaining_indentation' => true,
    'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
    'native_function_casing' => true,
    'no_extra_blank_lines' => ['tokens' => [
        'break',
        'case',
        'continue',
        'curly_brace_block',
        'default',
        'parenthesis_brace_block',
        'return',
        // 'square_brace_block', // this doesn't play nice with Laravel config files.
        'switch',
        'throw',
        'use',
    ]],
    'multiline_whitespace_before_semicolons' => true,
    'no_null_property_initialization' => true,
    'no_short_bool_cast' => true,
    'echo_tag_syntax' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_spaces_around_offset' => true,
    'no_trailing_comma_in_singleline_array' => true,
    'no_unused_imports' => true,
    'no_whitespace_in_blank_line' => true,
    'not_operator_with_successor_space' => true,
    'ordered_imports' => true,
    'phpdoc_var_annotation_correct_order' => true,
    'short_scalar_cast' => true,
    'single_blank_line_before_namespace' => true,
    'single_quote' => true,
    'trailing_comma_in_multiline' => true,
    'trim_array_spaces' => true,
    'visibility_required' => ['elements' => ['property']], // test methods are written without declaring visibility
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->name('*.php')
    ->exclude([
        'bootstrap',
        'node_modules',
        'public',
        'resources',
        'storage',
        'vendor',
    ]);

return (new PhpCsFixer\Config)
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder($finder);
