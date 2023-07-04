<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Business\Writer;

interface ProductOfferServiceStorageWriterInterface
{
    /**
     * @param list<int> $productOfferIds
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollection(array $productOfferIds): void;
}
