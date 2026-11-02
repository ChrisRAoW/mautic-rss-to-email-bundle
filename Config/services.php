<?php

declare(strict_types=1);

use Mautic\CoreBundle\DependencyInjection\MauticCoreExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->public();

    // Plain PHP classes that are instantiated manually and must not be registered as services.
    $excludes = [
        'Feed',
        'Helpers',
        'Parser',
        'Traits',
        'vendor',
    ];

    $services->load('MauticPlugin\\MauticRssToEmailBundle\\', '../')
        ->exclude('../{'.implode(',', array_merge(MauticCoreExtension::DEFAULT_EXCLUDES, $excludes)).'}');
};
