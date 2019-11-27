<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorage;

interface PriceProductOfferStorageWriterInterface
{
    /**
     * @param int[] $priceProductOfferIds
     *
     * @return void
     */
    public function publish(array $priceProductOfferIds): void;

    /**
     * @param int[] $priceProductOfferIdsWithOfferIds
     *
     * @return void
     */
    public function unpublish(array $priceProductOfferIdsWithOfferIds): void;

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishByProductIds(array $productIds): void;

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function unpublishByProductIds(array $productIds): void;
}
