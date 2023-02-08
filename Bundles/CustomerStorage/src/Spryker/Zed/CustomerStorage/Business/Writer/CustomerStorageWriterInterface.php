<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Business\Writer;

interface CustomerStorageWriterInterface
{
    /**
     * @param array<int, \Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCustomerInvalidatedStorageCollectionByCustomerEvents(array $eventEntityTransfers): void;
}
