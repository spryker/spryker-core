<?php

namespace SprykerFeature\Zed\Application\Business\Model\Messenger;

class ConsolePresenter extends AbstractPresenter implements
    ObservingPresenterInterface
{
    /**
     * @param MessengerInterface $messenger
     */
    public function __construct(MessengerInterface $messenger)
    {
        parent::__construct($messenger);

        $this->messenger->registerPresenter($this);
    }

    public function update()
    {
        $this->display();
    }

    public function display()
    {
        foreach ($this->messenger->getAll() as $message) {

        }
    }
}