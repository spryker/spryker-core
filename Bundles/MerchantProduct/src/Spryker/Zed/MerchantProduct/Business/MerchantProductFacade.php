<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MerchantProductCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductAbstractResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProduct\Business\MerchantProductBusinessFactory getFactory()
 */
class MerchantProductFacade extends AbstractFacade implements MerchantProductFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchant(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): ?MerchantTransfer
    {
        return $this->getRepository()->findMerchant($merchantProductCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductCollectionTransfer
     */
    public function get(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): MerchantProductCollectionTransfer
    {
        return $this->getRepository()->get($merchantProductCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateCartChange(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantProductCartValidator()
            ->validateCartChange($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstract(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ?ProductAbstractTransfer {
        return $this->getFactory()->createProductAbstractReader()->findProductAbstract($merchantProductCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractResponseTransfer
     */
    public function updateProductAbstract(MerchantProductTransfer $merchantProductTransfer): ProductAbstractResponseTransfer
    {
        return $this->getFactory()->createProductAbstractUpdater()->updateProductAbstract($merchantProductTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ProductConcreteCollectionTransfer {
        return $this->getFactory()->createProductConcreteReader()->getProductConcreteCollection($merchantProductCriteriaTransfer);
    }
}
