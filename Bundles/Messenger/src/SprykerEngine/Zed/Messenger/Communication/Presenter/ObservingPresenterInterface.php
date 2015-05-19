<?php

namespace SprykerEngine\Zed\Messenger\Communication\Presenter;

interface ObservingPresenterInterface extends PresenterInterface
{
    public function update();
}