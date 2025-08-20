<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Storage;

interface SspModelStorageWriterInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeSspModelStorageCollectionBySspModelEvents(array $eventEntityTransfers): void;

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeSspModelStorageCollectionBySspModelToProductListEvents(array $eventEntityTransfers): void;
}
