<?php

namespace SprykerEngine\Shared\Messenger\Communication\Presenter;

interface PresenterInterface
{
    /**
     * @return MessengerInterface
     */
    public function getMessenger();
}
