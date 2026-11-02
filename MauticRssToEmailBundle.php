<?php

declare(strict_types=1);

/*
 * @copyright   2018 Chris Schrijver. All rights reserved
 * @author      Chris Schrijver
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRssToEmailBundle;

use Mautic\PluginBundle\Bundle\PluginBundleBase;

// Load the bundled dependencies (SimplePie) when the plugin was installed by copying the folder.
// When installed through Composer the dependencies are available through the root autoloader.
if (is_file(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

class MauticRssToEmailBundle extends PluginBundleBase
{
}
