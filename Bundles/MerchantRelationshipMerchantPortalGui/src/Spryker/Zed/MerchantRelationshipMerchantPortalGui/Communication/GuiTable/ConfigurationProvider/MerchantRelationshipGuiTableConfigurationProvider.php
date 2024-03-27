<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiConfig;

class MerchantRelationshipGuiTableConfigurationProvider implements MerchantRelationshipGuiTableConfigurationProviderInterface
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
    public const PARAM_MERCHANT_RELATIONSHIP_ID = 'merchant-relationship-id';

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
    protected const FILTER_ID_IN_COMPANY_IDS = 'inCompanyIds';

    /**
     * @var string
     */
    protected const FILTER_ID_CREATED_AT = 'createdAt';

    /**
     * @var string
     */
    protected const FILTER_TITLE_IN_COMPANY_IDS = 'Company';

    /**
     * @var string
     */
    protected const FILTER_TITLE_CREATED_AT = 'Created';

    /**
     * @uses \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Controller\MerchantRelationshipController::tableDataAction()
     *
     * @var string
     */
    protected const DATA_URL = '/merchant-relationship-merchant-portal-gui/merchant-relationship/table-data';

    /**
     * @var string
     */
    protected const SEARCH_PLACEHOLDER = 'Search';

    /**
     * @var string
     */
    protected const ROW_ACTION_ID_UPDATE_MERCHANT_RELATIONSHIP = 'update-merchant-relationship';

    /**
     * @var string
     */
    protected const ROW_ACTION_TITLE_UPDATE_MERCHANT_RELATIONSHIP = 'Manage Relation';

    /**
     * @var string
     */
    protected const ROW_ACTION_URL_UPDATE_MERCHANT_RELATIONSHIP = '/merchant-relationship-merchant-portal-gui/update-merchant-relationship?%s=${row.%s}';

    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiConfig
     */
    protected MerchantRelationshipMerchantPortalGuiConfig $merchantRelationshipMerchantPortalGuiConfig;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface
     */
    protected MerchantRelationshipReaderInterface $merchantRelationshipRequestReader;

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected GuiTableFactoryInterface $guiTableFactory;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiConfig $merchantRelationshipMerchantPortalGuiConfig
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface $merchantRelationshipRequestReader
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     */
    public function __construct(
        MerchantRelationshipMerchantPortalGuiConfig $merchantRelationshipMerchantPortalGuiConfig,
        MerchantRelationshipReaderInterface $merchantRelationshipRequestReader,
        GuiTableFactoryInterface $guiTableFactory
    ) {
        $this->merchantRelationshipMerchantPortalGuiConfig = $merchantRelationshipMerchantPortalGuiConfig;
        $this->merchantRelationshipRequestReader = $merchantRelationshipRequestReader;
        $this->guiTableFactory = $guiTableFactory;
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
            ->setDefaultPageSize($this->merchantRelationshipMerchantPortalGuiConfig->getDefaultMerchantRelationRequestTablePageSize());

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
                $this->merchantRelationshipMerchantPortalGuiConfig->getMerchantRelationshipTableBusinessUnitsColumnLimit(),
                'gray',
            )
            ->addColumnDate(static::COL_KEY_CREATED_AT, static::COL_TITLE_CREATED_AT, true, true);

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
                $this->merchantRelationshipRequestReader->getInCompanyIdsFilterOptions(),
            )
            ->addFilterDateRange(
                static::FILTER_ID_CREATED_AT,
                static::FILTER_TITLE_CREATED_AT,
            );

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder
            ->addRowActionDrawerAjaxForm(
                static::ROW_ACTION_ID_UPDATE_MERCHANT_RELATIONSHIP,
                static::ROW_ACTION_TITLE_UPDATE_MERCHANT_RELATIONSHIP,
                sprintf(
                    static::ROW_ACTION_URL_UPDATE_MERCHANT_RELATIONSHIP,
                    static::PARAM_MERCHANT_RELATIONSHIP_ID,
                    static::COL_KEY_ID,
                ),
            )
            ->setRowClickAction(static::ROW_ACTION_ID_UPDATE_MERCHANT_RELATIONSHIP);

        return $guiTableConfigurationBuilder;
    }
}
