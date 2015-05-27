<?php

namespace SprykerEngine\Shared\Messenger\Communication\Presenter;

use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;

interface PresenterInterface
{

    /**
     * @return MessengerInterface
     */
    public function getMessenger();

}
