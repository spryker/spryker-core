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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return void
     */
    public function updateAbstractProductWithAlternativesAvailableLabel(int $idProduct): void
    {
        $this->getFactory()
            ->createProductAlternativeProductLabelWriter()
            ->updateAbstractProductWithAlternativesAvailableLabel($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabel(int $idProduct): void
    {
        $this->getFactory()
            ->createProductAlternativeProductLabelWriter()
            ->removeProductAbstractRelationsForLabel($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges(): array
    {
        return $this->getFactory()
            ->createProductAbstractRelationReader()
            ->findProductLabelProductAbstractRelationChanges();
    }
}
