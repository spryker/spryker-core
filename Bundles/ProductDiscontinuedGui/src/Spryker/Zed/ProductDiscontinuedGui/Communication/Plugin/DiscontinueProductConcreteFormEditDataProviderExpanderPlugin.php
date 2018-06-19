<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Communication\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditDataProviderExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinuedGui\Communication\ProductDiscontinuedGuiCommunicationFactory getFactory()
 */
class DiscontinueProductConcreteFormEditDataProviderExpanderPlugin extends AbstractPlugin implements ProductConcreteFormEditDataProviderExpanderPluginInterface
{
    /**
     * Specification:
     * - Adds discontinued product information to ProductConcreteEditForm data
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
        $discontinuedData = $this->getFactory()
            ->createDiscontinueProductFormDataProvider()
            ->getData($productConcrete->getIdProductConcrete());
        $formData = array_merge($formData, $discontinuedData);
    }
}
