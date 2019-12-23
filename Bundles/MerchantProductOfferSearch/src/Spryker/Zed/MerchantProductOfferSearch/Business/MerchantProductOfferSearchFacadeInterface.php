<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business;

interface MerchantProductOfferSearchFacadeInterface
{
    /**
     * Specification:
     *  - Retrieve list of abstract product ids by merchant ids.
     *
     * @api
     *
     * @param int[] $merchantIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantIds(array $merchantIds): array;

    /**
     * Specification:
     *  - Retrieve list of abstract product ids by merchant profile ids.
     *
     * @api
     *
     * @param int[] $merchantProfileIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantProfileIds(array $merchantProfileIds): array;

    /**
     * Specification:
     *  - Retrieve list of abstract product ids by product offer ids.
     *
     * @api
     *
     * @param int[] $productOfferIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductOfferIds(array $productOfferIds): array;
}
