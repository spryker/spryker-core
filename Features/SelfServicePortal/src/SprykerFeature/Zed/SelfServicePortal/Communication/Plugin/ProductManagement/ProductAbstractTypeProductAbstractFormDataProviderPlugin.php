<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductManagement;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormDataProviderExpanderPluginInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ProductAbstractTypeForm;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory getBusinessFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 */
class ProductAbstractTypeProductAbstractFormDataProviderPlugin extends AbstractPlugin implements ProductAbstractFormDataProviderExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands product abstract form data with product abstract types.
     * - Uses ProductAbstractTransfer to get required data.
     * - Returns modified form data array.
     *
     * @api
     *
     * @param array<string, mixed> $formData
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array<string, mixed>
     */
    public function expand(array $formData, ProductAbstractTransfer $productAbstractTransfer): array
    {
        if ($productAbstractTransfer->getIdProductAbstract()) {
            $formData[ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES] = $productAbstractTransfer->getProductAbstractTypes();
        }

        return $formData;
    }
}
