<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;

class ProductAttributesRestApiToProductAttributeClientBridge implements ProductAttributesRestApiToProductAttributeClientInterface
{
    /**
     * @var \Spryker\Client\ProductAttribute\ProductAttributeClientInterface
     */
    protected $productAttributeClient;

    /**
     * @param \Spryker\Client\ProductAttribute\ProductAttributeClientInterface $productAttributeClient
     */
    public function __construct($productAttributeClient)
    {
        $this->productAttributeClient = $productAttributeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer
     */
    public function getProductManagementAttributes(
        ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
    ): ProductManagementAttributeCollectionTransfer {
        return $this->productAttributeClient->getProductManagementAttributes($productManagementAttributeFilterTransfer);
    }
}
