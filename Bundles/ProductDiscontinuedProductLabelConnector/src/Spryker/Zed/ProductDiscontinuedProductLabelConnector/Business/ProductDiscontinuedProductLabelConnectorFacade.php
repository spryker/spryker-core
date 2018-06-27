<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelConnectorBusinessFactory getFactory()
 */
class ProductDiscontinuedProductLabelConnectorFacade extends AbstractFacade implements ProductDiscontinuedProductLabelConnectorFacadeInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function installProductDiscontinuedProductLabelConnector(): void
    {
        $this->getFactory()
            ->createProductDiscontinuedProductLabelConnectorInstaller()
            ->install();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function findAllLabels(): array
    {
        return $this->getFactory()
            ->getProductLabelFacade()
            ->findAllLabels();
    }

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return void
     */
    public function updateAbstractProductWithDiscontinuedLabel(int $idProduct): void
    {
        $this->getFactory()
            ->createProductDiscontinuedProductLabelWriter()
            ->updateAbstractProductWithDiscontinuedLabel($idProduct);
    }

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabel(int $idProduct): void
    {
        $this->getFactory()
            ->createProductDiscontinuedProductLabelWriter()
            ->removeProductAbstractRelationsForLabel($idProduct);
    }
}
