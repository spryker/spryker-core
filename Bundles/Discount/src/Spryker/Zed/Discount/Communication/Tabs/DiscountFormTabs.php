<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Tabs;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;
use Symfony\Component\Form\FormInterface;

class DiscountFormTabs extends AbstractTabs
{
    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $discountForm;

    /**
     * @var \Symfony\Component\Form\FormInterface|null
     */
    protected $voucherForm;

    /**
     * @var \Generated\Shared\Transfer\DiscountConfiguratorTransfer|null
     */
    protected $discountConfiguratorTransfer;

    /**
     * @param \Symfony\Component\Form\FormInterface $discountForm
     * @param \Symfony\Component\Form\FormInterface|null $voucherForm
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer|null $discountConfiguratorTransfer
     */
    public function __construct(
        FormInterface $discountForm,
        ?FormInterface $voucherForm = null,
        ?DiscountConfiguratorTransfer $discountConfiguratorTransfer = null
    ) {
        $this->discountForm = $discountForm;
        $this->voucherForm = $voucherForm;
        $this->discountConfiguratorTransfer = $discountConfiguratorTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer)
    {
        $this
            ->addGeneralInformationTab($tabsViewTransfer)
            ->addDiscountCalculationTab($tabsViewTransfer)
            ->addConditionsTab($tabsViewTransfer)
            ->addVoucherCodesTab($tabsViewTransfer);

        $tabsViewTransfer
            ->setFooterTemplate('@Discount/Index/partial/footer.twig')
            ->setIsNavigable(true);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addGeneralInformationTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('general')
            ->setTitle('General information')
            ->setTemplate('@Discount/Index/partial/general.twig');

        $this->setHasError($tabItemTransfer, DiscountConfiguratorTransfer::DISCOUNT_GENERAL);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addDiscountCalculationTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('discount')
            ->setTitle('Discount calculation')
            ->setTemplate('@Discount/Index/partial/calculation.twig');

        $this->setHasError($tabItemTransfer, DiscountConfiguratorTransfer::DISCOUNT_CALCULATOR);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addConditionsTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('conditions')
            ->setTitle('Conditions')
            ->setTemplate('@Discount/Index/partial/condition.twig');

        $this->setHasError($tabItemTransfer, DiscountConfiguratorTransfer::DISCOUNT_CONDITION);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addVoucherCodesTab(TabsViewTransfer $tabsViewTransfer)
    {
        if (!$this->isVoucherType()) {
            return $this;
        }

        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('voucher')
            ->setTitle('Voucher codes')
            ->setTemplate('@Discount/Index/partial/voucher.twig');

        if ($this->voucherForm->isSubmitted() && !$this->voucherForm->isValid()) {
            $tabItemTransfer->setHasError(true);
        }

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabItemTransfer $tabItemTransfer
     * @param string $subForm
     *
     * @return void
     */
    protected function setHasError(TabItemTransfer $tabItemTransfer, $subForm)
    {
        if ($this->discountForm->isSubmitted() && !$this->discountForm->get($subForm)->isValid()) {
            $tabItemTransfer->setHasError(true);
        }
    }

    /**
     * @return bool
     */
    protected function isVoucherType()
    {
        return $this->discountConfiguratorTransfer && $this->discountConfiguratorTransfer->getDiscountGeneral()->getDiscountType() == DiscountConstants::TYPE_VOUCHER;
    }
}
