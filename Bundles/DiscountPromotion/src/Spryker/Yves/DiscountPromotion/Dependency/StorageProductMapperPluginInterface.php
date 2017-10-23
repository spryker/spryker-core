<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DiscountPromotion\Dependency;

interface StorageProductMapperPluginInterface
{
    /**
     * @api
     *
     * Specification:
     * - This plugin should map product raw data from redis store, to StorageProductTransfer this mapping is provided in project code
     *
     * @param array $productStorageData
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapStorageProduct(array $productStorageData, array $selectedAttributes);
}
