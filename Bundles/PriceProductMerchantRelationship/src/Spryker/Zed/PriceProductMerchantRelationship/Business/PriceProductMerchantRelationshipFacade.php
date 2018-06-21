<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Business;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Business\PriceProductMerchantRelationshipBusinessFactory getFactory()
 */
class PriceProductMerchantRelationshipFacade extends AbstractFacade implements PriceProductMerchantRelationshipFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer[] $priceProductStoreEntityTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    public function matchValue(
        array $priceProductStoreEntityTransferCollection,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?MoneyValueTransfer {
        return $this->getFactory()
            ->createMerchantRelationshipPriceDecision()
            ->matchValue($priceProductStoreEntityTransferCollection, $priceProductCriteriaTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function savePriceProductMerchantRelationship(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->getFactory()
            ->createMerchantRelationshipPriceWriter()
            ->save($priceProductTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function deletePriceProductMerchantRelationshipByIdMerchantRelationship(int $idMerchantRelationship): void
    {
        $this->getFactory()
            ->createMerchantRelationshipPriceWriter()
            ->deleteByIdMerchantRelationship($idMerchantRelationship);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function deleteAllPriceProductMerchantRelationship(): void
    {
        $this->getFactory()
            ->createMerchantRelationshipPriceWriter()
            ->deleteAll();
    }
}
