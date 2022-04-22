<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Plugin\ProductMerchantPortalGui;

use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductMapperPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\PriceProductMerchantRelationshipMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\PriceProductMerchantRelationshipMerchantPortalGuiConfig getConfig()
 */
class MerchantRelationshipPriceProductMapperPlugin extends AbstractPlugin implements PriceProductMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps merchant relationship data to `PriceProductTableViewTransfer` transfer object.
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
    ): PriceProductTableViewTransfer {
        return $this->getFactory()
            ->createMerchantRelationshipPriceProductMapper()
            ->mapPriceProductTransferToPriceProductTableViewTransfer(
                $priceProductTransfer,
                $priceProductTableViewTransfer,
            );
    }

    /**
     * {@inheritDoc}
     * - Maps merchant relationship data to `PriceProductTransfer` transfer object.
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
    ): PriceProductTransfer {
        return $this->getFactory()
            ->createMerchantRelationshipPriceProductMapper()
            ->mapRequestDataToPriceProductTransfer($data, $priceProductTransfer);
    }

    /**
     * {@inheritDoc}
     * - Maps merchant relationship price table data to price product transfer.
     *
     * @api
     *
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapTableDataToPriceProductTransfer(
        array $data,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        return $this->getFactory()
            ->createMerchantRelationshipPriceProductMapper()
            ->mapTableDataToPriceProductTransfer($data, $priceProductTransfer);
    }

    /**
     * {@inheritDoc}
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
    ): PriceProductCriteriaTransfer {
        return $this->getFactory()
            ->createMerchantRelationshipPriceProductMapper()
            ->mapRequestDataToPriceProductCriteriaTransfer($data, $priceProductCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *  - Maps data for `PriceProductCollectionDeleteCriteriaTransfer.merchantRelationIds` and `PriceProductCollectionDeleteCriteriaTransfer.priceProductStoreIds` transfer properties.
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
    ): PriceProductCollectionDeleteCriteriaTransfer {
        return $this->getFactory()
            ->createMerchantRelationshipPriceProductMapper()
            ->mapPriceProductTransfersToPriceProductCollectionDeleteCriteriaTransfer(
                $priceProductTransfers,
                $priceProductCollectionDeleteCriteriaTransfer,
            );
    }
}
