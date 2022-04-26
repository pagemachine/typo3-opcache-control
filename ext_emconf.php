<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Opcache Control',
    'description' => 'PHP Opcache management for TYPO3',
    'category' => 'misc',
    'author' => 'Mathias Brodala',
    'author_email' => 'mbrodala@pagemachine.de',
    'author_company' => 'Pagemachine AG',
    'state' => 'alpha',
    'version' => '0.0.2',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.5.99',
        ],
    ],
];
