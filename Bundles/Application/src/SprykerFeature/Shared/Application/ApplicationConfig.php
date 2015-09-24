<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Application;

use SprykerFeature\Shared\Library\ConfigInterface;

interface ApplicationConfig extends ConfigInterface
{

    const NAVIGATION_CACHE_ENABLED = 'navigation cache enabled';

    const ZED_TWIG_OPTIONS = 'ZED_TWIG_OPTIONS'; // see http://twig.sensiolabs.org/doc/api.html#environment-options

    const YVES_TWIG_OPTIONS = 'YVES_TWIG_OPTIONS';

    const SHOW_SYMFONY_TOOLBAR = 'SHOW_SYMFONY_TOOLBAR';

}
