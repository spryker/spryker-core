<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditDataProviderExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CompanySupplierGui\Communication\CompanySupplierGuiCommunicationFactory getFactory()
 */
class ProductConcreteFormEditDataProviderExpanderPlugin extends AbstractPlugin implements ProductConcreteFormEditDataProviderExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcrete
     * @param array $formData
     *
     * @return void
     */
    public function expand(ProductConcreteTransfer $productConcrete, array &$formData): void
    {
        $formData[ProductConcreteTransfer::COMPANY_SUPPLIERS] =
            $this->getFactory()->getCompanySupplierFacade()->getSuppliersByIdProduct($productConcrete->getIdProductConcrete())->getSuppliers();
    }
}
