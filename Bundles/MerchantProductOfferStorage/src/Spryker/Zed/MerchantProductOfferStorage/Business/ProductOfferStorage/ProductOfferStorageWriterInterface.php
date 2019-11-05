<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductOfferStorage;

interface ProductOfferStorageWriterInterface
{
    /**
     * @param array $productOfferReferences
     *
     * @return void
     */
    public function publish(array $productOfferReferences): void;

    /**
     * @param array $productOfferReferences
     *
     * @return void
     */
    public function unpublish(array $productOfferReferences): void;
}
