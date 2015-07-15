<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Messenger\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

class TwigMessengerPlugin extends AbstractPlugin
{

    /**
     * @return TwigMessengerExtension
     */
    public function getTwigMessengerExtension()
    {
        return $this->getDependencyContainer()->createTwigMessengerExtension();
    }

}
