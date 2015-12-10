<?php

namespace SprykerEngine\Yves\Messenger;

use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerEngine\Yves\Messenger\Business\Model\Messenger;
use SprykerEngine\Yves\Messenger\Plugin\TwigMessengerExtension;

class MessengerDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return TwigMessengerExtension
     */
    public function createTwigMessengerExtension()
    {
        $twigMessengerExtension = new TwigMessengerExtension();
        $twigMessengerExtension->setMessenger(new Messenger());

        return $twigMessengerExtension;
    }

}
