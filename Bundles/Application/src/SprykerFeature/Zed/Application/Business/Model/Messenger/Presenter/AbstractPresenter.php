<?php

namespace SprykerFeature\Zed\Application\Business\Model\Messenger;

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