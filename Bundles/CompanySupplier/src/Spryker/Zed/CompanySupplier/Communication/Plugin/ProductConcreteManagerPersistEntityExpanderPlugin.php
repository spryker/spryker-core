<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplier\Communication\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanySupplier\Business\CompanySupplierFacadeInterface getFacade()
 */
class ProductConcreteManagerPersistEntityExpanderPlugin extends AbstractPlugin implements ProductConcreteManagerPersistEntityExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcrete
     *
     * @return void
     */
    public function persistRelatedData(ProductConcreteTransfer $productConcrete): void
    {
        $this->getFacade()->saveCompanySuppliersForProductConcrete($productConcrete);
    }
}
