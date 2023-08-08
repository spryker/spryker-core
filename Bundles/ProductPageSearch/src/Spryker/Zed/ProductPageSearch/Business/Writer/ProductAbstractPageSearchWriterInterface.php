<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Writer;

interface ProductAbstractPageSearchWriterInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductAbstractPageSearchCollectionByProductImageSetToProductImageEvents(array $eventEntityTransfers): void;
}
