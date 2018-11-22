<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Queue\Communication\Table\QueueProcessTable;

/**
 * @method \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Queue\QueueConfig getConfig()
 * @method \Spryker\Zed\Queue\Business\QueueFacadeInterface getFacade()
 */
class QueueCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Queue\Communication\Table\QueueProcessTable
     */
    public function createQueueProcessTable()
    {
        return new QueueProcessTable(
            $this->getQueryContainer()
        );
    }
}
