<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Plugin\Provider;

use Silex\ControllerProviderInterface as SilexControllerProviderInterface;

interface ControllerProviderInterface extends SilexControllerProviderInterface
{

    /**
     * Returns the url prefix that should be pre pendend to all
     * urls from this provider
     *
     * @return string
     */
    public function getUrlPrefix();

}
