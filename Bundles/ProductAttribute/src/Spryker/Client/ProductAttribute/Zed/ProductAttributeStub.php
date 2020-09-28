<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAttribute\Zed;

use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;
use Spryker\Client\ProductAttribute\Dependency\Client\ProductAttributeToZedRequestClientInterface;

class ProductAttributeStub implements ProductAttributeStubInterface
{
    /**
     * @var \Spryker\Client\ProductAttribute\Dependency\Client\ProductAttributeToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ProductAttribute\Dependency\Client\ProductAttributeToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ProductAttributeToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\ProductAttribute\Communication\Controller\GatewayController::getProductManagementAttributesAction()
     *
     * @param \Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer
     */
    public function getProductManagementAttributes(
        ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
    ): ProductManagementAttributeCollectionTransfer {
        /** @var \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer $productManagementAttributeCollectionTransfer */
        $productManagementAttributeCollectionTransfer = $this->zedRequestClient->call(
            '/product-attribute/gateway/get-product-management-attributes',
            $productManagementAttributeFilterTransfer
        );

        return $productManagementAttributeCollectionTransfer;
    }
}
