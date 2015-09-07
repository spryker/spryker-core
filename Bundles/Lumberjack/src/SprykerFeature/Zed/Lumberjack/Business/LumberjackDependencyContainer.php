<?php
/**
 *
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

    public function createEvent()
    {
        return $this->getFactory()->createModelEvent();
    }

    public function createEventJournal() {
        return $this->getFactory()->createModelEventJournal();
    }

}
