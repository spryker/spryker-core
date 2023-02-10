<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductMapperInterface
{
    /**
     * @param array<mixed> $newPriceProducts
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mapTableRowsToPriceProductTransfers(
        array $newPriceProducts,
        ArrayObject $priceProductTransfers
    ): ArrayObject;

    /**
     * @param array<string, mixed> $data
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mapRequestDataToPriceProductTransfers(
        array $data,
        ArrayObject $priceProductTransfers
    ): ArrayObject;

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer
     */
    public function mapPriceProductTransfersToPriceProductCollectionDeleteCriteriaTransfer(
        array $priceProductTransfers,
        PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
    ): PriceProductCollectionDeleteCriteriaTransfer;

    /**
     * @param array<mixed> $requestQueryParams
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function mapRequestDataToPriceProductCriteriaTransfer(
        array $requestQueryParams,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): PriceProductCriteriaTransfer;

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTableViewTransfer $priceProductTableViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTableViewTransfer
     */
    public function mapPriceProductTransferToPriceProductTableViewTransfer(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTableViewTransfer $priceProductTableViewTransfer
    ): PriceProductTableViewTransfer;
}
