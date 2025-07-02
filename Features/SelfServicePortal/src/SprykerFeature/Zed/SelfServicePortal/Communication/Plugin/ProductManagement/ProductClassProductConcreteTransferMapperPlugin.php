<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductManagement;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductFormTransferMapperExpanderPluginInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ProductClassForm;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 */
class ProductClassProductConcreteTransferMapperPlugin extends AbstractPlugin implements ProductFormTransferMapperExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps product class data from form to ProductConcreteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcrete
     * @param array<mixed> $formData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function map(ProductConcreteTransfer $productConcrete, array $formData): ProductConcreteTransfer
    {
        if (!isset($formData[ProductClassForm::FIELD_PRODUCT_CLASSES])) {
            return $productConcrete;
        }

        $productClasses = $formData[ProductClassForm::FIELD_PRODUCT_CLASSES];

        $productConcrete->setProductClasses($productClasses);

        return $productConcrete;
    }
}
