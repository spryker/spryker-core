<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface;

/**
 * @deprecated Will be removed without replacement.
 */
class ProductIdHydrator implements ProductIdHydratorInterface
{
    /**
     * @var \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface
     */
    protected $salesProductConnectorQueryContainer;

    /**
     * @param \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface $SalesProductConnectorQueryContainer
     */
    public function __construct(SalesProductConnectorQueryContainerInterface $SalesProductConnectorQueryContainer)
    {
        $this->salesProductConnectorQueryContainer = $SalesProductConnectorQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateProductIds(OrderTransfer $orderTransfer)
    {
        foreach ($orderTransfer->getItems() as $item) {
            $concreteProduct = $this->findConcreteProduct($item->getSku());

            if (!$concreteProduct) {
                continue;
            }

            $item->setIdProductAbstract($concreteProduct->getFkProductAbstract());
            $item->setId($concreteProduct->getIdProduct());
        }

        return $orderTransfer;
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct|null
     */
    protected function findConcreteProduct($sku)
    {
        return $this->salesProductConnectorQueryContainer
            ->queryProductConcreteBySku($sku)
            ->findOne();
    }
}
