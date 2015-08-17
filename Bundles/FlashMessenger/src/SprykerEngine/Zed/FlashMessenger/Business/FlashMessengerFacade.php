<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\FlashMessenger\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method FlashMessengerDependencyContainer getDependencyContainer()
 */
class FlashMessengerFacade extends AbstractFacade
{

    public function addSuccessMessage($message)
    {
        $this->getDependencyContainer()->createMessageTray()->addSuccessMessage($message);
    }

    public function addErrorMessage($message)
    {
        $this->getDependencyContainer()->createMessageTray()->addErrorMessage($message);
    }

    public function addInfoMessage($message)
    {
        $this->getDependencyContainer()->createMessageTray()->addInfoMessage($message);
    }

}
