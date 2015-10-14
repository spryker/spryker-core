<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerEngine\Shared\Application\Communication\Bootstrap\Extension;

use SprykerEngine\Shared\Application\Communication\Application;

interface TwigExtensionInterface
{

    /**
     * @param Application $app
     */
    public function getTwigExtensions(Application $app);

}
