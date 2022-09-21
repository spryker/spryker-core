<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Writer;

interface ProductConcretePageSearchByProductEventsWriterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductConcretePageSearchCollectionByProductEvents(array $eventEntityTransfers): void;
}
