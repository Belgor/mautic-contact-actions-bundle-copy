<?php

/*
 * @copyright   2020 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'routes' => [
    ],
    'menu' => [
    ],
    'services' => [
        'events' => [
            'mautic.contactactions.subscriber.lead' => [
                'class'     => \MauticPlugin\MauticContactActionsBundle\EventListener\LeadSubscriber::class,
                'arguments' => [
                    'mautic.contactactions.settings',
                    'mautic.lead.model.lead',
                ],
            ],
        ],
        'forms'  => [
        ],
        'models' => [
        ],
        'helpers'=> [
        ],
        'others'=> [
            'mautic.contactactions.settings' => [
                'class'     => \MauticPlugin\MauticContactActionsBundle\Integration\ContactActionsSettings::class,
                'arguments' => [
                    'mautic.helper.integration',
                ],
            ],
        ],
        'integrations' => [
            'mautic.integration.contactactions' => [
                'class'     => \MauticPlugin\MauticContactActionsBundle\Integration\ContactActionsIntegration::class,
            ],
        ],
    ],
];
