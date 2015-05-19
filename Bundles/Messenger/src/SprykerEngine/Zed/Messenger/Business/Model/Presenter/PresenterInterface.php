<?php

namespace SprykerEngine\Zed\Messenger\Business\Model\Presenter;

interface PresenterInterface
{
    /**
     * @return MessengerInterface
     */
    public function getMessenger();
}