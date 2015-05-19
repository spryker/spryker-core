<?php

namespace SprykerEngine\Zed\Messenger\Communication\Presenter;

interface PresenterInterface
{
    /**
     * @return MessengerInterface
     */
    public function getMessenger();
}