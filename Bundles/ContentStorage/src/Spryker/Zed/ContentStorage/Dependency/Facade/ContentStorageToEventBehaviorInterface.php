<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Dependency\Facade;

interface ContentStorageToEventBehaviorInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     *
     * @return array
     */
    public function getEventTransferIds(array $eventTransfers);

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return array
     */
    public function getEventTransferForeignKeys(array $eventTransfers, $foreignKeyColumnName);
}
