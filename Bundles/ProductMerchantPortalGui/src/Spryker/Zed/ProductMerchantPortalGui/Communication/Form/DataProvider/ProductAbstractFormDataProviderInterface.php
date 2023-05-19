<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantProductTransfer;

interface ProductAbstractFormDataProviderInterface
{
    /**
     * @param int $idProductAbstract
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer|null
     */
    public function findMerchantProduct(int $idProductAbstract, int $idMerchant): ?MerchantProductTransfer;

    /**
     * @return array<array<int>>
     */
    public function getOptions(): array;

    /**
     * @return array<array<string, mixed>>
     */
    public function getProductCategoryTree(): array;
}
