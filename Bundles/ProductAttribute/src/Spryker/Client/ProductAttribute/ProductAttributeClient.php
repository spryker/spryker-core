<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAttribute;

use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\ProductAttribute\Zed\ProductAttributeStubInterface;

/**
 * @method \Spryker\Client\ProductAttribute\ProductAttributeFactory getFactory()
 */
class ProductAttributeClient extends AbstractClient implements ProductAttributeClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer
     */
    public function getProductManagementAttributes(
        ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
    ): ProductManagementAttributeCollectionTransfer {
        return $this->getZedStub()->getProductManagementAttributes($productManagementAttributeFilterTransfer);
    }

    /**
     * @return \Spryker\Client\ProductAttribute\Zed\ProductAttributeStubInterface
     */
    protected function getZedStub(): ProductAttributeStubInterface
    {
        return $this->getFactory()->createProductAttributeStub();
    }
}
