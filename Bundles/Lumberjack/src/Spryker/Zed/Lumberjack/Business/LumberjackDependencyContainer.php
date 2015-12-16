<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Zed\Lumberjack\Business;

use Spryker\Zed\Lumberjack\Business\Model\EventJournal;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class LumberjackDependencyContainer extends AbstractBusinessFactory
{

    /**
     * @return Model\EventJournal
     */
    public function createEventJournal()
    {
        return new EventJournal();
    }

}
