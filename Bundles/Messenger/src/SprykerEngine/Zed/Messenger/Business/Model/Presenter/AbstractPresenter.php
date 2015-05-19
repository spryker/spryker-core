<?php

namespace SprykerEngine\Zed\Messenger\Business\Model\Presenter;

use SprykerEngine\Zed\Messenger\Business\Model\MessengerInterface;

class AbstractPresenter implements PresenterInterface
{
    /**
     * @var MessengerInterface
     */
    protected $messenger;

    public function __construct(MessengerInterface $messenger)
    {
        $this->messenger = $messenger;
    }
}