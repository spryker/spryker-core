<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Expander;

use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface;

class ProductApprovalStatusProductTableConfigurationExpander implements ProductApprovalStatusProductTableConfigurationExpanderInterface
{
    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DENIED
     *
     * @var string
     */
    protected const STATUS_DENIED = 'denied';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @var string
     */
    protected const TITLE_COLUMN_APPROVAL_STATUS = 'Approval Status';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade)
    {
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function expand(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setTitle(static::TITLE_COLUMN_APPROVAL_STATUS)
            ->setId(ProductAbstractTransfer::APPROVAL_STATUS)
            ->setType(GuiTableConfigurationBuilderInterface::COLUMN_TYPE_LIST)
            ->addTypeOption('color', 'gray')
            ->addTypeOption('type', GuiTableConfigurationBuilderInterface::COLUMN_TYPE_CHIP)
            ->addTypeOptionMapping('type', [
                $this->translatorFacade->trans('-') => GuiTableConfigurationBuilderInterface::COLUMN_TYPE_TEXT,
            ])
            ->addTypeOptionMapping('color', [
                $this->translatorFacade->trans(static::STATUS_WAITING_FOR_APPROVAL) => 'yellow',
                $this->translatorFacade->trans(static::STATUS_DENIED) => 'red',
                $this->translatorFacade->trans(static::STATUS_APPROVED) => 'green',
            ])
            ->setSortable(false)
            ->setHideable(true);

        $guiTableConfigurationTransfer->addColumn($guiTableColumnConfigurationTransfer);

        return $guiTableConfigurationTransfer;
    }
}
