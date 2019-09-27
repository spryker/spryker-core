<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Expander;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface;

class ConfigurableBundleTemplateSlotProductListExpander implements ConfigurableBundleTemplateSlotProductListExpanderInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface $productListFacade
     */
    public function __construct(ConfigurableBundleToProductListFacadeInterface $productListFacade)
    {
        $this->productListFacade = $productListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function expandConfigurableBundleTemplateSlotWithProductList(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotTransfer->requireProductList()
            ->getProductList()
            ->requireIdProductList();

        $productListTransfer = $this->productListFacade->getProductListById($configurableBundleTemplateSlotTransfer->getProductList());

        return $configurableBundleTemplateSlotTransfer->setProductList($productListTransfer);
    }
}
