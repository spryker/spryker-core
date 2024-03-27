<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Reader\MerchantRelationRequestReaderInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig;

class MerchantRelationRequestGuiTableConfigurationProvider implements MerchantRelationRequestGuiTableConfigurationProviderInterface
{
    /**
     * @var string
     */
    public const COL_KEY_ID = 'id';

    /**
     * @var string
     */
    public const COL_KEY_COMPANY = 'company';

    /**
     * @var string
     */
    public const COL_KEY_BUSINESS_UNIT_OWNER = 'businessUnitOwner';

    /**
     * @var string
     */
    public const COL_KEY_BUSINESS_UNITS = 'businessUnits';

    /**
     * @var string
     */
    public const COL_KEY_CREATED_AT = 'createdAt';

    /**
     * @var string
     */
    public const PARAM_MERCHANT_RELATION_REQUEST_ID = 'merchant-relation-request-id';

    /**
     * @var string
     */
    public const COL_KEY_STATUS = 'status';

    /**
     * @var string
     */
    protected const COL_TITLE_ID = 'ID';

    /**
     * @var string
     */
    protected const COL_TITLE_COMPANY = 'Company';

    /**
     * @var string
     */
    protected const COL_TITLE_BUSINESS_UNIT_OWNER = 'Business Unit Owner';

    /**
     * @var string
     */
    protected const COL_TITLE_BUSINESS_UNITS = 'Business Units';

    /**
     * @var string
     */
    protected const COL_TITLE_CREATED_AT = 'Created';

    /**
     * @var string
     */
    protected const COL_TITLE_STATUS = 'Status';

    /**
     * @var string
     */
    protected const FILTER_ID_IN_COMPANY_IDS = 'inCompanyIds';

    /**
     * @var string
     */
    protected const FILTER_ID_CREATED_AT = 'createdAt';

    /**
     * @var string
     */
    protected const FILTER_ID_IN_STATUSES = 'inStatuses';

    /**
     * @var string
     */
    protected const FILTER_TITLE_IN_COMPANY_IDS = 'Company';

    /**
     * @var string
     */
    protected const FILTER_TITLE_CREATED_AT = 'Created';

    /**
     * @var string
     */
    protected const FILTER_TITLE_IN_STATUSES = 'Status';

    /**
     * @var string
     */
    protected const ROW_ACTION_ID_UPDATE_MERCHANT_RELATION_REQUEST = 'update-merchant-relation-request';

    /**
     * @var string
     */
    protected const ROW_ACTION_TITLE_UPDATE_MERCHANT_RELATION_REQUEST = 'Manage Request';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Controller\UpdateMerchantRelationRequestController::indexAction()
     *
     * @var string
     */
    protected const ROW_ACTION_URL_UPDATE_MERCHANT_RELATION_REQUEST = '/merchant-relation-request-merchant-portal-gui/update-merchant-relation-request?%s=${row.%s}';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Controller\MerchantRelationRequestsController::tableDataAction()
     *
     * @var string
     */
    protected const DATA_URL = '/merchant-relation-request-merchant-portal-gui/merchant-relation-requests/table-data';

    /**
     * @var string
     */
    protected const SEARCH_PLACEHOLDER = 'Search';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_PENDING
     *
     * @var string
     */
    protected const STATUS_PENDING = 'pending';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_REJECTED
     *
     * @var string
     */
    protected const STATUS_REJECTED = 'rejected';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_CANCELED
     *
     * @var string
     */
    protected const STATUS_CANCELED = 'canceled';

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected GuiTableFactoryInterface $guiTableFactory;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Reader\MerchantRelationRequestReaderInterface
     */
    protected MerchantRelationRequestReaderInterface $merchantRelationRequestReader;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig
     */
    protected MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Reader\MerchantRelationRequestReaderInterface $merchantRelationRequestReader
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        MerchantRelationRequestReaderInterface $merchantRelationRequestReader,
        MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->merchantRelationRequestReader = $merchantRelationRequestReader;
        $this->translatorFacade = $translatorFacade;
        $this->merchantRelationRequestMerchantPortalGuiConfig = $merchantRelationRequestMerchantPortalGuiConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addFilters($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addRowActions($guiTableConfigurationBuilder);

        $guiTableConfigurationBuilder
            ->setDataSourceUrl(static::DATA_URL)
            ->setSearchPlaceholder(static::SEARCH_PLACEHOLDER)
            ->setDefaultPageSize($this->merchantRelationRequestMerchantPortalGuiConfig->getDefaultMerchantRelationRequestTablePageSize());

        return $guiTableConfigurationBuilder->createConfiguration();
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addColumnText(static::COL_KEY_ID, static::COL_TITLE_ID, true, true)
            ->addColumnText(static::COL_KEY_COMPANY, static::COL_TITLE_COMPANY, true, true)
            ->addColumnText(
                static::COL_KEY_BUSINESS_UNIT_OWNER,
                static::COL_TITLE_BUSINESS_UNIT_OWNER,
                true,
                true,
            )
            ->addColumnListChip(
                static::COL_KEY_BUSINESS_UNITS,
                static::COL_TITLE_BUSINESS_UNITS,
                false,
                true,
                $this->merchantRelationRequestMerchantPortalGuiConfig->getMerchantRelationRequestTableBusinessUnitsColumnLimit(),
                'gray',
            )
            ->addColumnDate(static::COL_KEY_CREATED_AT, static::COL_TITLE_CREATED_AT, true, true)
            ->addColumnChip(static::COL_KEY_STATUS, static::COL_TITLE_STATUS, true, true, 'green', $this->getStatusColorMapping());

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addFilters(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
    ): GuiTableConfigurationBuilderInterface {
        $guiTableConfigurationBuilder
            ->addFilterSelect(
                static::FILTER_ID_IN_COMPANY_IDS,
                static::FILTER_TITLE_IN_COMPANY_IDS,
                true,
                $this->merchantRelationRequestReader->getInCompanyIdsFilterOptions(),
            )
            ->addFilterSelect(
                static::FILTER_ID_IN_STATUSES,
                static::FILTER_TITLE_IN_STATUSES,
                true,
                $this->getStatusFilterOptions(),
            )
            ->addFilterDateRange(
                static::FILTER_ID_CREATED_AT,
                static::FILTER_TITLE_CREATED_AT,
            );

        return $guiTableConfigurationBuilder;
    }

    /**
     * @return array<string>
     */
    protected function getStatusFilterOptions(): array
    {
        return [
            static::STATUS_APPROVED => $this->translatorFacade->trans(static::STATUS_APPROVED),
            static::STATUS_CANCELED => $this->translatorFacade->trans(static::STATUS_CANCELED),
            static::STATUS_PENDING => $this->translatorFacade->trans(static::STATUS_PENDING),
            static::STATUS_REJECTED => $this->translatorFacade->trans(static::STATUS_REJECTED),
        ];
    }

    /**
     * @return array<string>
     */
    protected function getStatusColorMapping(): array
    {
        return [
            $this->translatorFacade->trans(static::STATUS_REJECTED) => 'red',
            $this->translatorFacade->trans(static::STATUS_CANCELED) => 'gray',
            $this->translatorFacade->trans(static::STATUS_PENDING) => 'yellow',
        ];
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addRowActionDrawerAjaxForm(
            static::ROW_ACTION_ID_UPDATE_MERCHANT_RELATION_REQUEST,
            static::ROW_ACTION_TITLE_UPDATE_MERCHANT_RELATION_REQUEST,
            sprintf(
                static::ROW_ACTION_URL_UPDATE_MERCHANT_RELATION_REQUEST,
                static::PARAM_MERCHANT_RELATION_REQUEST_ID,
                static::COL_KEY_ID,
            ),
        )->setRowClickAction(static::ROW_ACTION_ID_UPDATE_MERCHANT_RELATION_REQUEST);

        return $guiTableConfigurationBuilder;
    }
}
