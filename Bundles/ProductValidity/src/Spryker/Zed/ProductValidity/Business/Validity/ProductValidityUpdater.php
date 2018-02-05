<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Business\Validity;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface;

class ProductValidityUpdater implements ProductValidityUpdaterInterface
{
    /**
     * @var \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface
     */
    protected $productValidityQueryContainer;

    /**
     * @param \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface $productValidityQueryContainer
     */
    public function __construct(ProductValidityQueryContainerInterface $productValidityQueryContainer)
    {
        $this->productValidityQueryContainer = $productValidityQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function update(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $productConcreteTransfer->requireIdProductConcrete();

        $productValidityEntity = $this->productValidityQueryContainer
            ->queryProductValidityByIdProductConcrete($productConcreteTransfer->getIdProductConcrete())
            ->findOneOrCreate();

        $productValidityEntity->setValidFrom($productConcreteTransfer->getValidFrom());
        $productValidityEntity->setValidTo($productConcreteTransfer->getValidTo());

        $productValidityEntity->save();

        return $productConcreteTransfer;
    }
}
