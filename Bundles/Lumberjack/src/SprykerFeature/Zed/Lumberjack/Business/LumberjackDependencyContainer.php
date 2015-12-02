<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Lumberjack\Business;

use SprykerFeature\Zed\Lumberjack\Business\Model\EventJournal;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Generated\Zed\Ide\FactoryAutoCompletion\LumberjackBusiness;

/**
 * @method LumberjackBusiness getFactory
 */
class LumberjackDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Model\EventJournal
     */
    public function createEventJournal()
    {
        return new EventJournal();
    }

}
