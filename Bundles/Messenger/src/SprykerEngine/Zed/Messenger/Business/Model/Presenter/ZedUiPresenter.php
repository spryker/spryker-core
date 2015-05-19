<?php

namespace SprykerEngine\Zed\Messenger\Business\Model\Presenter;

class ZedUiPresenter extends AbstractPresenter
{
    public function display()
    {
        return $this->messenger->getAll();
    }
}