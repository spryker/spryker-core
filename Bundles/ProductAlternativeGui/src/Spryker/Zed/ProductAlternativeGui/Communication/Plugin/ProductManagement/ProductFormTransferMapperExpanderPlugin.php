<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication\Plugin\ProductManagement;

use Generated\Shared\Transfer\ProductAlternativeCreateRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductAlternativeGui\Communication\Form\AddProductAlternativeForm;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductFormTransferMapperExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductAlternativeGui\Communication\ProductAlternativeGuiCommunicationFactory getFactory()
 */
class ProductFormTransferMapperExpanderPlugin extends AbstractPlugin implements ProductFormTransferMapperExpanderPluginInterface
{
    /**
     * Specification:
     *  - Added product alternative create requests to product concrete transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcrete
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function map(ProductConcreteTransfer $productConcrete, array $formData): ProductConcreteTransfer
    {
        if (empty($formData[AddProductAlternativeForm::FIELD_PRODUCT_ALTERNATIVE])) {
            return $productConcrete;
        }
        foreach ($formData[AddProductAlternativeForm::FIELD_PRODUCT_ALTERNATIVE] as $alternativeSku) {
            $productAlternativeCreateRequestTransfer = (new ProductAlternativeCreateRequestTransfer())
                ->setIdProduct($productConcrete->getIdProductConcrete())
                ->setAlternativeSku($alternativeSku);

            $productConcrete->addProductAlternativeCreateRequest($productAlternativeCreateRequestTransfer);
        }

        return $productConcrete;
    }
}
