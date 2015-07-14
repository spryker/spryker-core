<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Messenger\Communication\Plugin;

use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;

class TwigMessenger extends AbstractPlugin
{

    /**
     * @return TwigMessengerExtension
     */
    public function getTwigMessengerExtension()
    {
        return $this->getDependencyContainer()->createTwigMessengerExtension();
    }

}
