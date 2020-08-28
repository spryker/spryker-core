<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDiscountConnector\Business\Label;

use Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToProductLabelInterface;

class LabelProvider implements LabelProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToProductLabelInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToProductLabelInterface $productLabelFacade
     */
    public function __construct(ProductLabelDiscountConnectorToProductLabelInterface $productLabelFacade)
    {
        $this->productLabelFacade = $productLabelFacade;
    }

    /**
     * @return string[]
     */
    public function findAllLabels()
    {
        $productLabelOptions = [];

        $productLabelTransfers = $this->productLabelFacade->findAllLabels();
        foreach ($productLabelTransfers as $productLabelTransfer) {
            $productLabelOptions[$productLabelTransfer->getName()] = $productLabelTransfer->getName();
        }

        return $productLabelOptions;
    }
}
