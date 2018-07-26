<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;

class ProductConcreteFormEditTabs extends ProductFormEditTabs
{
    /**
     * @var array|\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditTabsExpanderPluginInterface[]
     */
    protected $productConcreteFormEditTabsExpanderPlugins;

    /**
     * ProductConcreteFormEditTabs constructor.
     *
     * @param \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditTabsExpanderPluginInterface[] $productConcreteFormEditTabsExpanderPlugins
     */
    public function __construct(array $productConcreteFormEditTabsExpanderPlugins = [])
    {
        $this->productConcreteFormEditTabsExpanderPlugins = $productConcreteFormEditTabsExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer)
    {
        $this
            ->addGeneralTab($tabsViewTransfer)
            ->addPriceAndStockTab($tabsViewTransfer)
            ->addImageTab($tabsViewTransfer)
            ->addAssigneBundledProductsTab($tabsViewTransfer)
            ->setFooter($tabsViewTransfer);

        return $this->executeExpanderPlugins($tabsViewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addGeneralTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('general')
            ->setTitle('General')
            ->setTemplate('@ProductManagement/Product/_partials/variant-general-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addPriceAndStockTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('price')
            ->setTitle('Price & Stock')
            ->setTemplate('@ProductManagement/Variant/_partials/price-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAttributesTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('attributes')
            ->setTitle('Attributes')
            ->setTemplate('@ProductManagement/Variant/_partials/abstract-attribute-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addVariantsTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('variants')
            ->setTitle('Variants')
            ->setTemplate('@ProductManagement/Product/_partials/variant-tab-adding.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAssigneBundledProductsTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('bundled')
            ->setTitle('Assign bundled products')
            ->setTemplate('@ProductManagement/Product/_partials/product-bundles-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function executeExpanderPlugins(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        foreach ($this->productConcreteFormEditTabsExpanderPlugins as $concreteFormEditTabsExpanderPlugin) {
            $tabsViewTransfer = $concreteFormEditTabsExpanderPlugin->expand($tabsViewTransfer);
        }

        return $tabsViewTransfer;
    }
}
