<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Messenger\Plugin;

use SprykerEngine\Yves\Kernel\AbstractPlugin;

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
