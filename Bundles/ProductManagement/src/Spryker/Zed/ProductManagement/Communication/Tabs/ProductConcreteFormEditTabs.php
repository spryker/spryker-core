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
    protected const TEMPLATE_TAB_GENERAL = '@ProductManagement/Product/_partials/EditVariant/tab-general.twig';
    protected const TEMPLATE_TAB_BUNDLED_PRODUCTS = '@ProductManagement/Product/_partials/EditVariant/tab-product-bundles.twig';
    protected const TEMPLATE_TAB_ATTRIBUTES = '@ProductManagement/Variant/_partials/abstract-attribute-tab.twig';
    protected const TEMPLATE_TAB_PRICE_AND_STOCK = '@ProductManagement/Variant/_partials/price-tab.twig';
    protected const TEMPLATE_TAB_VARIANTS = '@ProductManagement/Product/_partials/variant-tab-adding.twig';

    /**
     * @var array|\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditTabsExpanderPluginInterface[]
     */
    protected $productConcreteFormEditTabsExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditTabsExpanderPluginInterface[] $productConcreteFormEditTabsExpanderPlugins
     */
    public function __construct(array $productConcreteFormEditTabsExpanderPlugins = [])
    {
        parent::__construct();
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
            ->setTemplate(static::TEMPLATE_TAB_GENERAL);

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
            ->setTemplate(static::TEMPLATE_TAB_PRICE_AND_STOCK);

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
            ->setTemplate(static::TEMPLATE_TAB_ATTRIBUTES);

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
            ->setTemplate(static::TEMPLATE_TAB_VARIANTS);

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
            ->setTemplate(static::TEMPLATE_TAB_BUNDLED_PRODUCTS);

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
