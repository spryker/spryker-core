<?php

namespace SprykerEngine\Zed\Translation\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;

class TranslationDependencyContainer extends AbstractDependencyContainer
{
    public function getFacade()
    {
        return $this->getLocator()->translation()->facade();
    }
}