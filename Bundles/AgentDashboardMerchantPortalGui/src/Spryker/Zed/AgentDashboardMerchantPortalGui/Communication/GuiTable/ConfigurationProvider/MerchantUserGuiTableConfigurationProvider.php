<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\AgentDashboardMerchantPortalGui\AgentDashboardMerchantPortalGuiConfig;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface;

class MerchantUserGuiTableConfigurationProvider implements MerchantUserGuiTableConfigurationProviderInterface
{
    /**
     * @var string
     */
    public const COL_KEY_MERCHANT_NAME = 'merchantName';

    /**
     * @var string
     */
    public const COL_KEY_MERCHANT_STATUS = 'merchantStatus';

    /**
     * @var string
     */
    public const COL_KEY_FIRST_NAME = 'firstName';

    /**
     * @var string
     */
    public const COL_KEY_LAST_NAME = 'lastName';

    /**
     * @var string
     */
    public const COL_KEY_USERNAME = 'username';

    /**
     * @var string
     */
    public const COL_USERNAME_ENCODED = 'usernameEncoded';

    /**
     * @var string
     */
    public const COL_KEY_STATUS = 'status';

    /**
     * @var string
     */
    public const COL_KEY_ASSIST_USER = 'assistUser';

    /**
     * @var string
     */
    protected const COL_TITLE_MERCHANT = 'Merchant';

    /**
     * @var string
     */
    protected const COL_TITLE_MERCHANT_STATUS = 'Merchant Approval';

    /**
     * @var string
     */
    protected const COL_TITLE_FIRST_NAME = 'First Name';

    /**
     * @var string
     */
    protected const COL_TITLE_LAST_NAME = 'Last Name';

    /**
     * @var string
     */
    protected const COL_TITLE_EMAIL = 'Email';

    /**
     * @var string
     */
    protected const COL_TITLE_STATUS = 'User Status';

    /**
     * @var string
     */
    protected const COL_TITLE_ASSIST_USER = '';

    /**
     * @var string
     */
    protected const COL_TEXT_ASSIST_USER = 'Assist User';

    /**
     * @var string
     */
    protected const COL_MODAL_TITLE_ASSIST_USER = 'Start Merchant User Assistance';

    /**
     * @var string
     */
    protected const COL_MODAL_DESCRIPTION_ASSIST_USER = 'Log in as ${row.%s}';

    /**
     * @uses \Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\Controller\MerchantUsersController::tableDataAction()
     *
     * @var string
     */
    protected const DATA_URL = '/agent-dashboard-merchant-portal-gui/merchant-users/table-data';

    /**
     * @uses \Spryker\Zed\SecurityMerchantPortalGui\Communication\Controller\LoginController::indexAction()
     *
     * @var string
     */
    protected const LOGIN_URL = '/security-merchant-portal-gui/login';

    /**
     * @var string
     */
    protected const FORMAT_STRING_ASSIST_USER_URL = '%s?_switch_user=${row.%s}';

    /**
     * @var string
     */
    protected const SEARCH_PLACEHOLDER = 'Search';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const MERCHANT_STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_DENIED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_DENIED = 'denied';

    /**
     * @var string
     */
    protected const USER_STATUS_ACTIVE = 'active';

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected GuiTableFactoryInterface $guiTableFactory;

    /**
     * @var \Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @var \Spryker\Zed\AgentDashboardMerchantPortalGui\AgentDashboardMerchantPortalGuiConfig
     */
    protected AgentDashboardMerchantPortalGuiConfig $agentDashboardMerchantPortalGuiConfig;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\AgentDashboardMerchantPortalGui\AgentDashboardMerchantPortalGuiConfig $agentDashboardMerchantPortalGuiConfig
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        AgentDashboardMerchantPortalGuiConfig $agentDashboardMerchantPortalGuiConfig
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->translatorFacade = $translatorFacade;
        $this->agentDashboardMerchantPortalGuiConfig = $agentDashboardMerchantPortalGuiConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);

        $guiTableConfigurationBuilder
            ->setDataSourceUrl(static::DATA_URL)
            ->setSearchPlaceholder(static::SEARCH_PLACEHOLDER)
            ->setDefaultPageSize($this->agentDashboardMerchantPortalGuiConfig->getDefaultMerchantUserTablePageSize());

        return $guiTableConfigurationBuilder->createConfiguration();
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addColumnText(static::COL_KEY_MERCHANT_NAME, static::COL_TITLE_MERCHANT, true, true)
            ->addColumnChip(static::COL_KEY_MERCHANT_STATUS, static::COL_TITLE_MERCHANT_STATUS, true, true, 'green', [
                $this->translatorFacade->trans(static::MERCHANT_STATUS_WAITING_FOR_APPROVAL) => 'yellow',
                $this->translatorFacade->trans(static::MERCHANT_STATUS_DENIED) => 'red',
            ])
            ->addColumnText(static::COL_KEY_FIRST_NAME, static::COL_TITLE_FIRST_NAME, true, true)
            ->addColumnText(static::COL_KEY_LAST_NAME, static::COL_TITLE_LAST_NAME, true, true)
            ->addColumnText(static::COL_KEY_USERNAME, static::COL_TITLE_EMAIL, true, true)
            ->addColumnChip(static::COL_KEY_STATUS, static::COL_TITLE_STATUS, true, true, 'red', [
                static::USER_STATUS_ACTIVE => 'green',
            ])
            ->addColumnButtonAction(
                static::COL_KEY_ASSIST_USER,
                static::COL_TITLE_ASSIST_USER,
                false,
                true,
                static::COL_TEXT_ASSIST_USER,
                sprintf(static::FORMAT_STRING_ASSIST_USER_URL, static::LOGIN_URL, static::COL_USERNAME_ENCODED),
                static::COL_MODAL_TITLE_ASSIST_USER,
                sprintf(static::COL_MODAL_DESCRIPTION_ASSIST_USER, static::COL_KEY_USERNAME),
            );

        return $guiTableConfigurationBuilder;
    }
}
