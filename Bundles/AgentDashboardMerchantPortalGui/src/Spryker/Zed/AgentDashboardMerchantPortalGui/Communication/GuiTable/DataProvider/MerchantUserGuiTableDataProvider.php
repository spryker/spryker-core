<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantUserGuiTableConfigurationProvider;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\Mapper\MerchantUserGuiTableMapperInterface;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface;

class MerchantUserGuiTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @var string
     */
    protected const RESPONSE_DATA_KEY_ID_USER = 'idUser';

    /**
     * @var \Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected AgentDashboardMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @var \Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @var \Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\Mapper\MerchantUserGuiTableMapperInterface
     */
    protected MerchantUserGuiTableMapperInterface $merchantUserGuiTableMapper;

    /**
     * @var array<\Spryker\Zed\AgentDashboardMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserTableDataExpanderPluginInterface>
     */
    protected array $merchantUserTableDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\Mapper\MerchantUserGuiTableMapperInterface $merchantUserGuiTableMapper
     * @param array<\Spryker\Zed\AgentDashboardMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserTableDataExpanderPluginInterface> $merchantUserTableDataExpanderPlugins
     */
    public function __construct(
        AgentDashboardMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        MerchantUserGuiTableMapperInterface $merchantUserGuiTableMapper,
        array $merchantUserTableDataExpanderPlugins
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->translatorFacade = $translatorFacade;
        $this->merchantUserGuiTableMapper = $merchantUserGuiTableMapper;
        $this->merchantUserTableDataExpanderPlugins = $merchantUserTableDataExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return new MerchantUserTableCriteriaTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $merchantUserCriteriaTransfer = $this->merchantUserGuiTableMapper
            ->mapMerchantUserTableCriteriaTransferToMerchantUserCriteriaTransfer(
                $criteriaTransfer,
                new MerchantUserCriteriaTransfer(),
            );
        $merchantUserCollectionTransfer = $this->merchantUserFacade->getMerchantUserCollection(
            $merchantUserCriteriaTransfer,
        );

        $guiTableDataResponseTransfer = $this->merchantUserGuiTableMapper
            ->mapMerchantUserCollectionTransferToGuiTableDataResponseTransfer(
                $merchantUserCollectionTransfer,
                new GuiTableDataResponseTransfer(),
            );

        foreach ($merchantUserCollectionTransfer->getMerchantUsers() as $merchantUserTransfer) {
            $guiTableDataResponseTransfer->addRow($this->getRowData($merchantUserTransfer));
        }

        return $this->executeMerchantUserTableDataExpanderPlugins($guiTableDataResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableRowDataResponseTransfer
     */
    protected function getRowData(MerchantUserTransfer $merchantUserTransfer): GuiTableRowDataResponseTransfer
    {
        $merchantTransfer = $merchantUserTransfer->getMerchantOrFail();
        $userTransfer = $merchantUserTransfer->getUserOrFail();
        $merchantStatus = $merchantTransfer->getStatus()
            ? $this->translatorFacade->trans($merchantTransfer->getStatus())
            : $merchantTransfer->getStatus();

        $rowData = [
            MerchantUserGuiTableConfigurationProvider::COL_KEY_MERCHANT_NAME => $merchantTransfer->getName(),
            MerchantUserGuiTableConfigurationProvider::COL_KEY_MERCHANT_STATUS => $merchantStatus,
            MerchantUserGuiTableConfigurationProvider::COL_KEY_FIRST_NAME => $userTransfer->getFirstName(),
            MerchantUserGuiTableConfigurationProvider::COL_KEY_LAST_NAME => $userTransfer->getLastName(),
            MerchantUserGuiTableConfigurationProvider::COL_KEY_USERNAME => $userTransfer->getUsername(),
            MerchantUserGuiTableConfigurationProvider::COL_KEY_STATUS => $userTransfer->getStatus(),
            MerchantUserGuiTableConfigurationProvider::COL_KEY_ASSIST_USER => true,
            MerchantUserGuiTableConfigurationProvider::COL_USERNAME_ENCODED => rawurlencode($userTransfer->getUsernameOrFail()),
            static::RESPONSE_DATA_KEY_ID_USER => $userTransfer->getIdUserOrFail(),
        ];

        return (new GuiTableRowDataResponseTransfer())->setResponseData($rowData);
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function executeMerchantUserTableDataExpanderPlugins(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer
    ): GuiTableDataResponseTransfer {
        foreach ($this->merchantUserTableDataExpanderPlugins as $merchantUserTableDataExpanderPlugin) {
            $guiTableDataResponseTransfer = $merchantUserTableDataExpanderPlugin->expand($guiTableDataResponseTransfer);
        }

        return $guiTableDataResponseTransfer;
    }
}
