<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\ProductQuantityRestrictionsValidator;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityClientInterface;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityStorageClientInterface;

class ProductQuantityRestrictionsValidator implements ProductQuantityRestrictionsValidatorInterface
{
    /**
     * @var \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityClientInterface
     */
    protected $productQuantityClient;

    /**
     * @var \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityStorageClientInterface
     */
    protected $productQuantityStorageClient;

    /**
     * @param \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityClientInterface $productQuantityClient
     * @param \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityStorageClientInterface $productQuantityStorageClient
     */
    public function __construct(
        QuickOrderToProductQuantityClientInterface $productQuantityClient,
        QuickOrderToProductQuantityStorageClientInterface $productQuantityStorageClient
    ) {
        $this->productQuantityClient = $productQuantityClient;
        $this->productQuantityStorageClient = $productQuantityStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer
     */
    public function validateQuantityRestrictions(QuickOrderItemTransfer $quickOrderItemTransfer): ProductQuantityValidationResponseTransfer
    {
        $productConcreteTransfer = $this->createProductConcreteTransferFromQuickOrderItemTransfer($quickOrderItemTransfer);

        if (!$productConcreteTransfer->getIdProductConcrete()) {
            return $this->createValidationResponse(false);
        }

        $productQuantityStorageTransfer = $this->productQuantityStorageClient->findProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());

        if ($productQuantityStorageTransfer === null) {
            return $this->createValidationResponse(true);
        }

        return $this->productQuantityClient->validateProductQuantityRestrictions(
            (int)$quickOrderItemTransfer->getQty(),
            $this->createProductQuantityTransferFromProductQuantityStorageTransfer($productQuantityStorageTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductConcreteTransferFromQuickOrderItemTransfer(QuickOrderItemTransfer $quickOrderItemTransfer): ProductConcreteTransfer
    {
        return (new ProductConcreteTransfer())->fromArray(
            $quickOrderItemTransfer->toArray(),
            true
        );
    }

    /**
     * @param bool $validity
     *
     * @return \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer
     */
    protected function createValidationResponse(bool $validity): ProductQuantityValidationResponseTransfer
    {
        return (new ProductQuantityValidationResponseTransfer())->setIsValid($validity);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer $productQuantityStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer
     */
    protected function createProductQuantityTransferFromProductQuantityStorageTransfer(ProductQuantityStorageTransfer $productQuantityStorageTransfer): ProductQuantityTransfer
    {
        return (new ProductQuantityTransfer())->fromArray(
            $productQuantityStorageTransfer->toArray(),
            true
        );
    }
}
