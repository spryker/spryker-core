<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Creator;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface;

class ProductOfferShipmentTypeCreator implements ProductOfferShipmentTypeCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface
     */
    protected ProductOfferShipmentTypeEntityManagerInterface $productOfferShipmentTypeEntityManager;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface $productOfferShipmentTypeEntityManager
     */
    public function __construct(ProductOfferShipmentTypeEntityManagerInterface $productOfferShipmentTypeEntityManager)
    {
        $this->productOfferShipmentTypeEntityManager = $productOfferShipmentTypeEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createProductOfferShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($productOfferTransfer): void {
            $this->executeCreateProductOfferShipmentTypesTransaction($productOfferTransfer);
        });

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return void
     */
    protected function executeCreateProductOfferShipmentTypesTransaction(ProductOfferTransfer $productOfferTransfer): void
    {
        foreach ($productOfferTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            $this->productOfferShipmentTypeEntityManager->createProductOfferShipmentType(
                $productOfferTransfer->getIdProductOfferOrFail(),
                $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
            );
        }
    }
}
