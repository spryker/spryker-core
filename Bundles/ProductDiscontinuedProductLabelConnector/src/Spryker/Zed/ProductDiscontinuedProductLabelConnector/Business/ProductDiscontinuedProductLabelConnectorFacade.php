<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence\ProductDiscontinuedProductLabelConnectorRepositoryInterface getRepository()
 */
class ProductDiscontinuedProductLabelConnectorFacade extends AbstractFacade implements ProductDiscontinuedProductLabelConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
            ->createProductDiscontinuedProductLabelWriter()
            ->removeProductAbstractRelationsForLabel($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer>
     */
    public function findProductLabelProductAbstractRelationChanges(): array
    {
        return $this->getFactory()
            ->createProductAbstractRelationReader()
            ->findProductLabelProductAbstractRelationChanges();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productConcreteIds
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabelInBulk(array $productConcreteIds): void
    {
        $this->getFactory()
            ->createProductDiscontinuedProductLabelWriter()
            ->removeProductAbstractRelationsForLabelInBulk($productConcreteIds);
    }
}
