<?php

namespace SprykerFeature\Zed\Application\Business\Model\Messenger\Presenter;

use SprykerFeature\Zed\Application\Business\Model\Messenger\MessengerInterface;

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