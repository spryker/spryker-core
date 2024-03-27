<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\DataProvider\MerchantRelationshipDashboardGuiTableDataProviderInterface;

class MerchantRelationshipDashboardGuiTableConfigurationProvider implements MerchantRelationshipDashboardGuiTableConfigurationProviderInterface
{
    /**
     * @var string
     */
    public const COL_KEY_COMPANY = 'company';

    /**
     * @var string
     */
    public const COL_KEY_RELATIONS = 'relations';

    /**
     * @var string
     */
    public const COL_KEY_VIEW = 'view';

    /**
     * @var string
     */
    protected const COL_TITLE_COMPANY = 'Company';

    /**
     * @var string
     */
    protected const COL_TITLE_RELATIONS = 'Relations';

    /**
     * @var string
     */
    protected const COL_TITLE_VIEW = '';

    /**
     * @var string
     */
    protected const COL_TEXT_VIEW = 'View';

    /**
     * @var string
     */
    protected const URL_MERCHANT_RELATIONSHIP = '/merchant-relationship-merchant-portal-gui/merchant-relationship?%s';

    /**
     * @var string
     */
    protected const URL_PARAM_TABLE_MERCHANT_RELATIONSHIP = 'table-merchant-relationship={"page":1,"filter":{"inCompanyIds":["${row.%s}"]}}';

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected GuiTableFactoryInterface $guiTableFactory;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\DataProvider\MerchantRelationshipDashboardGuiTableDataProviderInterface
     */
    protected MerchantRelationshipDashboardGuiTableDataProviderInterface $merchantRelationshipDashboardGuiTableDataProvider;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\DataProvider\MerchantRelationshipDashboardGuiTableDataProviderInterface $merchantRelationshipDashboardGuiTableDataProvider
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        MerchantRelationshipDashboardGuiTableDataProviderInterface $merchantRelationshipDashboardGuiTableDataProvider
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->merchantRelationshipDashboardGuiTableDataProvider = $merchantRelationshipDashboardGuiTableDataProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
        // @phpstan-ignore-next-line
        $guiTableConfigurationBuilder->setDataSourceInlineData($this->merchantRelationshipDashboardGuiTableDataProvider
                ->fetchData($merchantRelationshipCollectionTransfer))
            ->setIsPaginationEnabled(false)
            ->isColumnConfiguratorEnabled(false)
            ->setIsTotalEnabled(false)
            ->isSearchEnabled(false);

        $guiTableConfigurationTransfer = $guiTableConfigurationBuilder->createConfiguration();
        $guiTableConfigurationTransfer->getDataSourceOrFail()
            ->setType(GuiTableConfigurationBuilderInterface::DATA_SOURCE_TYPE_INLINE_TABLE);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addColumns(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
    ): GuiTableConfigurationBuilderInterface {
        $guiTableConfigurationBuilder
            ->addColumnText(static::COL_KEY_COMPANY, static::COL_TITLE_COMPANY, false, false)
            ->addColumnText(static::COL_KEY_RELATIONS, static::COL_TITLE_RELATIONS, false, false)
            ->addColumnButtonAction(
                static::COL_KEY_VIEW,
                static::COL_TITLE_VIEW,
                false,
                false,
                static::COL_TEXT_VIEW,
                sprintf(static::URL_MERCHANT_RELATIONSHIP, sprintf(static::URL_PARAM_TABLE_MERCHANT_RELATIONSHIP, CompanyTransfer::ID_COMPANY)),
            );

        return $guiTableConfigurationBuilder;
    }
}
