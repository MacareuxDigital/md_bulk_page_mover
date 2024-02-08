<?php
defined('C5_EXECUTE') or die('Access Denied.');
/**
 * @author: Biplob Hossain <biplob.ice@gmail.com>
 */

return [
    'path_from' => '/path-from',
    'path_to' => '/path-to',
    'filters' => [
        'attributes' => [
            'attribute_1' => [
                'value' => 'value',
                'comparison' => '=',
            ],
            'attribute_2' => 'value',
            // Add more attribute filters as needed
        ],
        'date_added' => [
            'start' => 'YYYY-MM-DD',
            'end' => 'YYYY-MM-DD',
        ],
        'date_modified' => [
            'start' => 'YYYY-MM-DD',
            'end' => 'YYYY-MM-DD',
        ],
        'date_public' => [
            'start' => 'YYYY-MM-DD',
            'end' => 'YYYY-MM-DD',
        ],
        'full_text_keywords' => '',
        'keywords' => '',
        'page_type_id' => '', // array | integer
        'page_type_handle' => '', // array | string
        'user_id' => 1, // integer
    ],
];
