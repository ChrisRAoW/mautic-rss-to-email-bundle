<?php 

return [
    'name'        => 'RSS To Email',
    'description' => 'Enables RSS feed in mail',
    'version'     => '1.0',
    'author'      => 'Right Amount of Weird',
    'services' => [
        'events' => [
            'mautic.plugin.rsstoemail.subscriber' => [
                'class'     => 'MauticPlugin\MauticRssToEmailBundle\EventListener\EmailSubscriber',
            ],
        ],
    ],
];