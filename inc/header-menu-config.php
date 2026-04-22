<?php
/**
 * Manual neutral navigation defaults.
 */

function boilerplate_get_header_menu_config()
{
    return [
        [
            'label' => 'Lorem',
            'url' => '/',
            'kicker' => 'Lorem',
            'description' => 'Lorem ipsum dolor sit amet.',
        ],
        [
            'label' => 'Ipsum',
            'url' => '/#ipsum',
            'kicker' => 'Ipsum',
            'description' => 'Consectetur adipiscing elit.',
        ],
        [
            'label' => 'Dolor',
            'url' => '/#dolor',
            'kicker' => 'Dolor',
            'description' => 'Sed do eiusmod tempor.',
            'children' => [
                [
                    'label' => 'Sit',
                    'url' => '/#sit',
                    'description' => 'Ut labore et dolore.',
                ],
                [
                    'label' => 'Amet',
                    'url' => '/#amet',
                    'description' => 'Magna aliqua.',
                ],
            ],
        ],
    ];
}
