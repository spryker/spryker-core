<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelCollector\Dependency\Facade;

use Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface;

class ProductLabelCollectorToProductLabelBridge implements ProductLabelCollectorToProductLabelInterface
{

    /**
     * @var \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface $productLabelFacade
     */
    public function __construct(ProductLabelFacadeInterface $productLabelFacade)
    {
        $this->productLabelFacade = $productLabelFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function findAllLabels()
    {
        return $this->productLabelFacade->findAllLabels();
    }

}
