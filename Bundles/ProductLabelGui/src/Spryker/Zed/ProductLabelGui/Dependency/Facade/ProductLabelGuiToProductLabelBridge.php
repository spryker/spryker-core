<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductLabelResponseTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;

class ProductLabelGuiToProductLabelBridge implements ProductLabelGuiToProductLabelInterface
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
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findLabelById($idProductLabel)
    {
        return $this->productLabelFacade->findLabelById($idProductLabel);
    }

    /**
     * @return array<\Generated\Shared\Transfer\ProductLabelTransfer>
     */
    public function findAllLabels()
    {
        return $this->productLabelFacade->findAllLabels();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function createLabel(ProductLabelTransfer $productLabelTransfer)
    {
        $this->productLabelFacade->createLabel($productLabelTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function updateLabel(ProductLabelTransfer $productLabelTransfer)
    {
        $this->productLabelFacade->updateLabel($productLabelTransfer);
    }

    /**
     * @param int $idProductLabel
     * @param array<int> $idsProductAbstract
     *
     * @return void
     */
    public function addAbstractProductRelationsForLabel($idProductLabel, array $idsProductAbstract)
    {
        $this->productLabelFacade->addAbstractProductRelationsForLabel($idProductLabel, $idsProductAbstract);
    }

    /**
     * @param int $idProductLabel
     * @param array<int> $idsProductAbstract
     *
     * @return void
     */
    public function removeAbstractProductRelationsForLabel($idProductLabel, array $idsProductAbstract)
    {
        $this->productLabelFacade->removeProductAbstractRelationsForLabel($idProductLabel, $idsProductAbstract);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelResponseTransfer
     */
    public function removeLabel(ProductLabelTransfer $productLabelTransfer): ProductLabelResponseTransfer
    {
        return $this->productLabelFacade->removeLabel($productLabelTransfer);
    }

    /**
     * @param int $idProductLabel
     *
     * @return array<int>
     */
    public function findProductAbstractRelationsByIdProductLabel($idProductLabel)
    {
        return $this->productLabelFacade->findProductAbstractRelationsByIdProductLabel($idProductLabel);
    }
}
