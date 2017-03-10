<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Queue\Communication\Table\QueueProcessTable;
use Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface;

/**
 * @method QueueQueryContainerInterface getQueryContainer()
 */
class QueueCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return QueueProcessTable
     */
    public function createQueueProcessTable()
    {
        return new QueueProcessTable(
            $this->getQueryContainer()
        );
    }
}
