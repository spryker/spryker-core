<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelConnectorBusinessFactory getFactory()
 */
class ProductAlternativeProductLabelConnectorFacade extends AbstractFacade implements ProductAlternativeProductLabelConnectorFacadeInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function installProductAlternativeProductLabelConnector(): void
    {
        $this->getFactory()
            ->createProductAlternativeProductLabelConnectorInstaller()
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
    public function updateAbstractProductWithAlternativesAvailableLabel(int $idProduct): void
    {
/*
        $idProductAbstract = $this->getFactory()->getProductFacade()->getProductAbstractIdByConcreteId($idProduct);
        $productConcreteTransfer = $this->getFactory()
            ->getProductFacade()
            ->getConcreteProductsByAbstractProductId($idProductAbstract);

        $this->getFactory()->getProductLabelFacade()->findLabelByLabelName();*/
    }
}
