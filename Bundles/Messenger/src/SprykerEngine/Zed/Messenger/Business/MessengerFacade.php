<?php

namespace SprykerEngine\Zed\Messenger\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

class MessengerFacade extends AbstractFacade
{
    public function addSuccess($key, array $options = [])
    {
        $this->getDependencyContainer()->getMessenger()->addSuccess($key, $options);
    }

    public function getAll($type = null)
    {
        return $this->getDependencyContainer()->getMessenger()->getAll($type);
    }
}