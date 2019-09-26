<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplier\Communication\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface;

/**
 * @method \Spryker\Zed\CompanySupplier\Business\CompanySupplierFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanySupplier\CompanySupplierConfig getConfig()
 */
class SupplierProductConcreteAfterUpdatePlugin extends AbstractPlugin implements ProductConcretePluginUpdateInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcrete
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function update(ProductConcreteTransfer $productConcrete): ProductConcreteTransfer
    {
        return $this->getFacade()->saveCompanySupplierRelationsForProductConcrete($productConcrete);
    }
}
