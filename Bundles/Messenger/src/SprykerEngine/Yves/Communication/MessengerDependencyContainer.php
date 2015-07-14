<?php

namespace SprykerEngine\Yves\Messenger\Communication;

use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerEngine\Yves\Messenger\Communication\Plugin\TwigMessengerExtension;

class MessengerDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return TwigMessengerExtension
     */
    public function createTwigMessengerExtension()
    {
        $twigMessengerExtension = $this->getFactory()->createPluginTwigMessengerExtension();

        $twigMessengerExtension->setMessenger(
            $this->getFactory()->createBusinessModelMessenger()
        );

        return $twigMessengerExtension;
    }

}
