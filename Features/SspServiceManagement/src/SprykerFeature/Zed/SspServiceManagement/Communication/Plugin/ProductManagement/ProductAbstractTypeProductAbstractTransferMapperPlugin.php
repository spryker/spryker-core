<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\ProductManagement;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractTransferMapperPluginInterface;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\ProductAbstractTypeForm;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 */
class ProductAbstractTypeProductAbstractTransferMapperPlugin extends AbstractPlugin implements ProductAbstractTransferMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps product abstract type data from form to ProductAbstractTransfer.
     *
     * @api
     *
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function map(array $data, ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        if (!isset($data[ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES])) {
            return $productAbstractTransfer;
        }

        $productAbstractTypes = $data[ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES];

        $productAbstractTransfer->setProductAbstractTypes($productAbstractTypes);

        return $productAbstractTransfer;
    }
}
