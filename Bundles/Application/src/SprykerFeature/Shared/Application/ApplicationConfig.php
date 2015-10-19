<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Application;

use SprykerFeature\Shared\Library\ConfigInterface;

interface ApplicationConfig extends ConfigInterface
{

    const ALLOW_INTEGRATION_CHECKS = 'ALLOW_INTEGRATION_CHECKS';
    const COUCHBASE_BUCKET_PREFIX = 'COUCHBASE_BUCKET_PREFIX';
    const DISPLAY_ERRORS = 'DISPLAY_ERRORS';

    const ENABLE_APPLICATION_DEBUG = 'ENABLE_APPLICATION_DEBUG';
    const ENABLE_WEB_PROFILER = 'ENABLE_WEB_PROFILER';
    const NAVIGATION_CACHE_ENABLED = 'navigation cache enabled';
    const SET_REPEAT_DATA = 'SET_REPEAT_DATA';
    const SHOW_SYMFONY_TOOLBAR = 'SHOW_SYMFONY_TOOLBAR';
    const STORE_PREFIX = 'STORE_PREFIX';
    // see http://twig.sensiolabs.org/doc/api.html#environment-options
    const YVES_TWIG_OPTIONS = 'YVES_TWIG_OPTIONS';
    const ZED_TWIG_OPTIONS = 'ZED_TWIG_OPTIONS';

}
