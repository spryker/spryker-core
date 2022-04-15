<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

/**
 * Implement this plugin to map the price product information.
 */
interface PriceProductMapperPluginInterface
{
    /**
     * Specification:
     * - Maps `PriceProductTransfer` objects to `PriceProductCollectionDeleteCriteriaTransfer` transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTableViewTransfer $priceProductTableViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTableViewTransfer
     */
    public function mapPriceProductTransferToPriceProductTableViewTransfer(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTableViewTransfer $priceProductTableViewTransfer
    ): PriceProductTableViewTransfer;

    /**
     * Specification:
     * - Maps request data to `PriceProductTransfer` transfer object.
     *
     * @api
     *
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapRequestDataToPriceProductTransfer(
        array $data,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer;

    /**
     * Specification:
     * - Maps table data to `PriceProductTransfer` transfer object.
     *
     * @api
     *
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapTableDataToPriceProductTransfer(array $data, PriceProductTransfer $priceProductTransfer): PriceProductTransfer;

    /**
     * Specification:
     * - Maps request data to `PriceProductCriteriaTransfer` transfer object.
     *
     * @api
     *
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function mapRequestDataToPriceProductCriteriaTransfer(
        array $data,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): PriceProductCriteriaTransfer;

    /**
     * Specification:
     * - Maps `PriceProductTransfer` objects to `PriceProductCollectionDeleteCriteriaTransfer` transfer object.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer
     */
    public function mapPriceProductTransfersToPriceProductCollectionDeleteCriteriaTransfer(
        array $priceProductTransfers,
        PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
    ): PriceProductCollectionDeleteCriteriaTransfer;
}
