<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication\Plugin\ProductManagement;

use Generated\Shared\Transfer\ProductAlternativeToPersistTransfer;
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
     * {@inheritdoc}
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
        $productAlternativeToPersist = (new ProductAlternativeToPersistTransfer())
            ->setIdProduct($productConcrete->getIdProductConcrete())
            ->setSuggest($formData[AddProductAlternativeForm::FIELD_PRODUCT_NAME_OR_SKU_AUTOCOMPLETE]);

        $productConcrete->setProductAlternativeToPersist($productAlternativeToPersist);

        return $productConcrete;
    }
}
