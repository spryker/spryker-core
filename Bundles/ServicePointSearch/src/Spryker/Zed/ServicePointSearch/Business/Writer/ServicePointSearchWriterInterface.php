<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Business\Writer;

interface ServicePointSearchWriterInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServicePointEvents(array $eventTransfers): void;

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServicePointAddressEvents(array $eventTransfers): void;

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServicePointStoreEvents(array $eventTransfers): void;
}
