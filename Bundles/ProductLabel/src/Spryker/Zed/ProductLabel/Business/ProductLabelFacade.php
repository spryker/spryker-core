<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @api
 *
 * @method \Spryker\Zed\ProductLabel\Business\ProductLabelBusinessFactory getFactory()
 */
class ProductLabelFacade extends AbstractFacade implements ProductLabelFacadeInterface
{

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @throws \Spryker\Zed\ProductLabel\Business\Exception\MissingProductLabelException
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function readLabel($idProductLabel)
    {
        return $this
            ->getFactory()
            ->createLabelReader()
            ->read($idProductLabel);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function readLabelsForAbstractProduct($idProductAbstract)
    {
        return $this
            ->getFactory()
            ->createLabelReader()
            ->readAllForAbstractProduct($idProductAbstract);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function createLabel(ProductLabelTransfer $productLabelTransfer)
    {
        $this
            ->getFactory()
            ->createLabelWriter()
            ->create($productLabelTransfer);
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function setAbstractProductRelationForLabel($idProductLabel, $idProductAbstract)
    {
        $this
            ->getFactory()
            ->createProductRelationWriter()
            ->setRelation($idProductLabel, $idProductAbstract);
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function removeAbstractProductRelationForLabel($idProductLabel, $idProductAbstract)
    {
        $this
            ->getFactory()
            ->createProductRelationWriter()
            ->removeRelation($idProductLabel, $idProductAbstract);
    }

}
