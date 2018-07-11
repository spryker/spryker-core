<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade;

use Generated\Shared\Transfer\ProductLabelTransfer;

class ProductDiscontinuedProductLabelConnectorToProductLabelBridge implements ProductDiscontinuedProductLabelConnectorToProductLabelFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface $productLabelFacade
     */
    public function __construct($productLabelFacade)
    {
        $this->productLabelFacade = $productLabelFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function createLabel(ProductLabelTransfer $productLabelTransfer): void
    {
        $this->productLabelFacade->createLabel($productLabelTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function updateLabel(ProductLabelTransfer $productLabelTransfer): void
    {
        $this->productLabelFacade->updateLabel($productLabelTransfer);
    }

    /**
     * @param string $labelName
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findLabelByLabelName(string $labelName): ?ProductLabelTransfer
    {
        return $this->productLabelFacade->findLabelByLabelName($labelName);
    }

    /**
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabel(int $idProductLabel, array $idsProductAbstract): void
    {
        $this->productLabelFacade->removeProductAbstractRelationsForLabel($idProductLabel, $idsProductAbstract);
    }

    /**
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return void
     */
    public function addAbstractProductRelationsForLabel(int $idProductLabel, array $idsProductAbstract): void
    {
        $this->productLabelFacade->addAbstractProductRelationsForLabel($idProductLabel, $idsProductAbstract);
    }

    /**
     * @param int $idsProductAbstract
     *
     * @return int[]
     */
    public function findActiveLabelIdsByIdProductAbstract(int $idsProductAbstract): array
    {
        return $this->productLabelFacade->findActiveLabelIdsByIdProductAbstract($idsProductAbstract);
    }
}
