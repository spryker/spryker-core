<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

interface ProductConcreteMapperInterface
{
    /**
     * @param array $concreteProducts
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function mapRequestDataToProductConcreteTransfer(array $concreteProducts): array;
}
