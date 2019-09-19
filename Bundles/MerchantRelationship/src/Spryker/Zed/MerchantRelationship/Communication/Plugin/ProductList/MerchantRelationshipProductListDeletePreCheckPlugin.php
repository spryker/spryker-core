<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Communication\Plugin\ProductList;

use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListDeletePreCheckPluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationship\Communication\MerchantRelationshipCommunicationFactory getFactory()
 */
class MerchantRelationshipProductListDeletePreCheckPlugin extends AbstractPlugin implements ProductListDeletePreCheckPluginInterface
{
    /**
     * {@inheritdoc}
     * - Finds merchant relationships which use given product list by ProductListTransfer::idProductList.
     * - Returns ProductListResponseTransfer with check results.
     * - ProductListResponseTransfer::isSuccessful is equal to true when usage cases were not found, false otherwise.
     * - ProductListResponseTransfer::messages contains usage details.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function execute(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        return $this->getFacade()->checkProductListUsageAmongMerchantRelationships($productListTransfer);
    }
}
