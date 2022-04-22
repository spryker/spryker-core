<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Communication\Plugin\PriceProduct;

use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCollectionResponseTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipCollectionDeleteCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductCollectionDeletePluginInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Business\PriceProductMerchantRelationshipFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Communication\PriceProductMerchantRelationshipCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig getConfig()
 */
class MerchantRelationshipPriceProductCollectionDeletePlugin extends AbstractPlugin implements PriceProductCollectionDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Removes price product merchant relationships by provided criteria transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCollectionResponseTransfer
     */
    public function deletePriceProductCollection(
        PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
    ): PriceProductCollectionResponseTransfer {
        $priceProductMerchantRelationshipCollectionResponseTransfer = $this->getFacade()
            ->deletePriceProductMerchantRelationshipCollection(
                (new PriceProductMerchantRelationshipCollectionDeleteCriteriaTransfer())->fromArray(
                    $priceProductCollectionDeleteCriteriaTransfer->toArray(),
                    true,
                ),
            );

        return (new PriceProductCollectionResponseTransfer())->fromArray(
            $priceProductMerchantRelationshipCollectionResponseTransfer->toArray(),
            true,
        );
    }
}
