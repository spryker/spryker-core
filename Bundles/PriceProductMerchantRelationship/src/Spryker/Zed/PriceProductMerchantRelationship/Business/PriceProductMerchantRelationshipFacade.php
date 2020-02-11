<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Business;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Business\PriceProductMerchantRelationshipBusinessFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipRepositoryInterface getRepository()
 */
class PriceProductMerchantRelationshipFacade extends AbstractFacade implements PriceProductMerchantRelationshipFacadeInterface
{
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    public function expandPriceProductDimension(PriceProductDimensionTransfer $priceProductDimensionTransfer): PriceProductDimensionTransfer
    {
        return $this->getFactory()
            ->createPriceProductDimensionExpander()
            ->expand($priceProductDimensionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated will be removed without replacement.
     *
     * @param int $idPriceProductStore
     *
     * @return void
     */
    public function deletePriceProductMerchantRelationshipByIdPriceProductStore(int $idPriceProductStore): void
    {
        $this->getFactory()
            ->createMerchantRelationshipPriceWriter()
            ->deleteByIdPriceProductStore($idPriceProductStore);
    }
}
