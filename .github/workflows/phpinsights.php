<?php

declare(strict_types=1);

return [
    'preset' => 'default',
    'exclude' => [
        'tests'
    ],
    'add' => [
        //  ExampleMetric::class => [
        //      ExampleInsight::class,
        //  ]
    ],
    'remove' => [
        //  ExampleInsight::class,
    ],
    'config' => [
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class => [
            'lineLimit' => 160,
            'absoluteLineLimit' => 160
        ]
    ],
];
