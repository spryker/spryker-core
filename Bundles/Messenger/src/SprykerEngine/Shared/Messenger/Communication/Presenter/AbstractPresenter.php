<?php

namespace SprykerEngine\Shared\Messenger\Communication\Presenter;

use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;

class AbstractPresenter implements PresenterInterface
{
    /**
     * @var MessengerInterface
     */
    protected $messenger;

    /**
     * @param MessengerInterface $messenger
     */
    public function __construct(MessengerInterface $messenger)
    {
        $this->messenger = $messenger;
    }

    /**
     * @return MessengerInterface
     */
    public function getMessenger()
    {
        return $this->messenger;
    }
}
