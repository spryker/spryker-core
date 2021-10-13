<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

interface ProductConcreteMapperInterface
{
    /**
     * @param array<mixed> $productConcreteData
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function mapProductConcreteDataToProductConcreteTransfers(
        array $productConcreteData,
        array $productConcreteTransfers
    ): array;
}
