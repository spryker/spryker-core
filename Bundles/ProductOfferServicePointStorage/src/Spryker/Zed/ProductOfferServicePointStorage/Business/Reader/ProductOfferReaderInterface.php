<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Business\Reader;

interface ProductOfferReaderInterface
{
    /**
     * @param list<int> $productOfferIds
     *
     * @return list<string>
     */
    public function getProductOfferReferencesByProductOfferIds(array $productOfferIds): array;
}
