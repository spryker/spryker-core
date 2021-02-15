<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractFormDataProviderInterface
{
    /**
     * @param int $idProductAbstract
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstract(int $idProductAbstract, int $idMerchant): ?ProductAbstractTransfer;

    /**
     * @return int[][]
     */
    public function getOptions(): array;

    /**
     * @return mixed[]
     */
    public function getProductCategoryTree(): array;
}
