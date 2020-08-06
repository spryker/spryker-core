<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class ProductRelationTabs extends AbstractTabs
{
    protected const RELATION_TAB_NAME = 'relation-type';
    protected const RELATION_TAB_TEMPLATE = '@ProductRelationGui/_partial/tab-relation-type.twig';
    protected const RELATION_TAB_TITLE = 'Settings';

    protected const ASSIGN_TAB_NAME = 'assign-products';
    protected const ASSIGN_TAB_TEMPLATE = '@ProductRelationGui/_partial/tab-assign-products.twig';
    protected const ASSIGN_TAB_TITLE = 'Products';

    protected const FOOTER_TEMPLATE = '@ProductRelationGui/_partial/form-submit.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addRelationTypeTab($tabsViewTransfer)
            ->addAssignProductsTab($tabsViewTransfer)
            ->setFooter($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addRelationTypeTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer->setName(static::RELATION_TAB_NAME);
        $tabItemTransfer->setTemplate(static::RELATION_TAB_TEMPLATE);
        $tabItemTransfer->setTitle(static::RELATION_TAB_TITLE);
        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAssignProductsTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer->setName(static::ASSIGN_TAB_NAME);
        $tabItemTransfer->setTemplate(static::ASSIGN_TAB_TEMPLATE);
        $tabItemTransfer->setTitle(static::ASSIGN_TAB_TITLE);
        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function setFooter(TabsViewTransfer $tabsViewTransfer)
    {
        $tabsViewTransfer->setFooterTemplate(static::FOOTER_TEMPLATE)
            ->setIsNavigable(true);

        return $this;
    }
}
