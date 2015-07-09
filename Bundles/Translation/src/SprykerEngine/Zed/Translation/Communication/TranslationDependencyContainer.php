<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Translation\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;

class TranslationDependencyContainer extends AbstractCommunicationDependencyContainer
{

    public function getFacade()
    {
        return $this->getLocator()->translation()->facade();
    }

}
