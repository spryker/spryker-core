<?php

namespace SprykerEngine\Yves\Messenger\Communication;

use SprykerEngine\Yves\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerEngine\Yves\Messenger\Communication\Plugin\TwigMessengerExtension;

class MessengerDependencyContainer extends AbstractCommunicationDependencyContainer
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
