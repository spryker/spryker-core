<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\TaxProductConnector\Business\Exception\ProductAbstractNotFoundException;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface;

class ProductAbstractTaxWriter
{
    /**
     * @var \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface $queryContainer
     */
    public function __construct(TaxProductConnectorQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productConcreteTransfer
     *
     * @throws \Spryker\Zed\TaxProductConnector\Business\Exception\ProductAbstractNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function saveTaxSetToProductAbstract(ProductAbstractTransfer $productConcreteTransfer)
    {
        $productConcreteTransfer->requireIdProductAbstract();

        $productAbstractEntity = $this->queryContainer
            ->queryProductAbstractById($productConcreteTransfer->getIdProductAbstract())
            ->findOne();

        if ($productAbstractEntity === null) {
            throw new ProductAbstractNotFoundException(
                sprintf(
                    'Could not assign tax set, product abstract with id "%d" not found.',
                    $productConcreteTransfer->getIdProductAbstract()
                )
            );
        }

        $productAbstractEntity->setFkTaxSet($productConcreteTransfer->getIdTaxSet());
        $productAbstractEntity->save();

        return $productConcreteTransfer;
    }
}
