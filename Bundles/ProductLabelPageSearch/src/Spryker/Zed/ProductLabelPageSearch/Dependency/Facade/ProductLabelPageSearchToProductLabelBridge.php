<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelPageSearch\Dependency\Facade;

class ProductLabelPageSearchToProductLabelBridge implements ProductLabelPageSearchToProductLabelInterface
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
     * @param int $idProductAbstract
     *
     * @return \int[]
     */
    public function findLabelIdsByIdProductAbstract($idProductAbstract)
    {
        return $this->productLabelFacade->findLabelIdsByIdProductAbstract($idProductAbstract);
    }

}
