<?php

namespace SprykerFeature\Zed\Application\Business\Model\Messenger;

interface ObservingPresenterInterface extends PresenterInterface
{
    public function update();
}