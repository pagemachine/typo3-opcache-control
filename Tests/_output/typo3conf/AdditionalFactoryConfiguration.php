<?php

return [
    'LOG' => [
        'Pagemachine' => [
            'OpcacheControl' => [
                'writerConfiguration' => [
                    \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
                        \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                            'logFileInfix' => 'opcache_control',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
