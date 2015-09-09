<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Lumberjack\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Generated\Zed\Ide\FactoryAutoCompletion\LumberjackBusiness;

/**
 * @method LumberjackBusiness getFactory
 */
class LumberjackDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return DTO\Event
     */
    public function createEvent()
    {
        return $this->getFactory()->createModelEvent();
    }

    /**
     * @return DTO\EventJournal
     */
    public function createEventJournal()
    {
        return $this->getFactory()->createModelEventJournal();
    }

}
