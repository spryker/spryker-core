<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;
use Symfony\Component\Form\FormInterface;

class OptionTabs extends AbstractTabs
{

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $productOptionGroupForm;

    /**
     * @param \Symfony\Component\Form\FormInterface $productOptionGroupForm
     */
    public function __construct(FormInterface $productOptionGroupForm)
    {
        $this->productOptionGroupForm = $productOptionGroupForm;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer)
    {
        $this->addGeneralTab($tabsViewTransfer)
            ->addProductTab($tabsViewTransfer)
            ->setFooter($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addGeneralTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer->setName('general');
        $tabItemTransfer->setTemplate('@ProductOption/_partial/tab-general.twig');
        $tabItemTransfer->setTitle('General Information');
        $tabsViewTransfer->addTab($tabItemTransfer);

        $this->setHasError($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addProductTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer->setName('products');
        $tabItemTransfer->setTemplate('@ProductOption/_partial/tab-products.twig');
        $tabItemTransfer->setTitle('Products');
        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabItemTransfer $tabItemTransfer
     *
     * @return void
     */
    protected function setHasError(TabItemTransfer $tabItemTransfer)
    {
        if ($this->productOptionGroupForm->isSubmitted() && !$this->productOptionGroupForm->isValid()) {
            $tabItemTransfer->setHasError(true);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function setFooter(TabsViewTransfer $tabsViewTransfer)
    {
        $tabsViewTransfer->setFooterTemplate('@ProductOption/_template/_form-submit.twig')
            ->setIsNavigable(true);

        return $this;
    }

}
