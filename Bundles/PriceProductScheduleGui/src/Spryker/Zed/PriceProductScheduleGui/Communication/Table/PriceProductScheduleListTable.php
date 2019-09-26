<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use DateTimeZone;
use Orm\Zed\PriceProductSchedule\Persistence\Map\SpyPriceProductScheduleListTableMap;
use Orm\Zed\PriceProductSchedule\Persistence\Map\SpyPriceProductScheduleTableMap;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;

class PriceProductScheduleListTable extends AbstractTable
{
    protected const PATTERN_DATE_TIME = 'Y-m-d e H:i:s';

    protected const TEMPLATE_FULL_NAME = '%s %s';
    protected const TEMPLATE_IMPORTED_BY = '%s <br/> %s';

    protected const EXPRESSION_COUNT = 'COUNT(%s)';
    protected const EXPRESSION_DISTINCT_COUNT_ADDITION = 'COUNT(DISTINCT %s) + COUNT(DISTINCT %s)';

    protected const COL_IMPORT_ID = 'id_price_product_schedule_list';
    protected const COL_NAME = 'name';
    protected const COL_IMPORTED_BY = 'created_at';
    protected const COL_STATUS = 'is_active';
    protected const COL_NUMBER_OF_PRICES = 'number_of_prices';
    protected const COL_NUMBER_OF_PRODUCTS = 'number_of_products';
    protected const COL_ACTIONS = 'actions';

    protected const STATUS_DRAFT = 'Draft';
    protected const STATUS_PUBLISHED = 'Published';

    protected const HEADER_IMPORT_ID = 'Import ID';
    protected const HEADER_NAME = 'Name';
    protected const HEADER_IMPORTED_BY = 'Imported by and on the';
    protected const HEADER_STATUS = 'Status';
    protected const HEADER_NUMBER_OF_PRICES = 'Number of prices';
    protected const HEADER_NUMBER_OF_PRODUCTS = 'Number of products';
    protected const HEADER_ACTIONS = 'Actions';

    protected const BUTTON_VIEW = 'View';
    protected const BUTTON_EDIT = 'Edit';
    protected const BUTTON_DOWNLOAD = 'Download';
    protected const BUTTON_DELETE = 'Delete';

    protected const DEFAULT_IMPORTED_BY_VALUE = 'N/A';

    public const PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST = 'id-price-product-schedule-list';

    public const URL_PRICE_PRODUCT_SCHEDULE_LIST_VIEW = '/price-product-schedule-gui/view-schedule-list';
    public const URL_PRICE_PRODUCT_SCHEDULE_LIST_EDIT = '/price-product-schedule-gui/edit-schedule-list';
    public const URL_PRICE_PRODUCT_SCHEDULE_LIST_DOWNLOAD = '/price-product-schedule-gui/download-schedule-list';
    public const URL_PRICE_PRODUCT_SCHEDULE_LIST_DELETE = '/price-product-schedule-gui/delete-schedule-list';
    public const URL_PRICE_PRODUCT_SCHEDULE_LIST_DRY_RUN_IMPORT = '/price-product-schedule-gui/dry-run-import';

    /**
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery
     */
    protected $priceProductScheduleListQuery;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery $priceProductScheduleListQuery
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        SpyPriceProductScheduleListQuery $priceProductScheduleListQuery,
        PriceProductScheduleGuiToStoreFacadeInterface $storeFacade
    ) {
        $this->priceProductScheduleListQuery = $priceProductScheduleListQuery;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_IMPORT_ID => static::HEADER_IMPORT_ID,
            static::COL_NAME => static::HEADER_NAME,
            static::COL_IMPORTED_BY => static::HEADER_IMPORTED_BY,
            static::COL_STATUS => static::HEADER_STATUS,
            static::COL_NUMBER_OF_PRICES => static::HEADER_NUMBER_OF_PRICES,
            static::COL_NUMBER_OF_PRODUCTS => static::HEADER_NUMBER_OF_PRODUCTS,
            static::COL_ACTIONS => static::HEADER_ACTIONS,
        ]);

        $config->setSearchable([
            static::COL_NAME,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
            static::COL_IMPORTED_BY,
        ]);

        $config->setSortable([
            static::COL_IMPORT_ID,
            static::COL_NAME,
            static::COL_IMPORTED_BY,
            static::COL_STATUS,
            static::COL_NUMBER_OF_PRICES,
            static::COL_NUMBER_OF_PRODUCTS,
        ]);

        $config->setDefaultSortField(static::COL_IMPORT_ID, TableConfiguration::SORT_DESC);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this->prepareQuery();

        $priceProductScheduleListCollection = $this->runQuery($query, $config, true);

        return $this->mapPriceProductScheduleListCollectionToTableDataArray($priceProductScheduleListCollection, []);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $priceProductScheduleListCollection
     * @param array $priceProductScheduleList
     *
     * @return array
     */
    protected function mapPriceProductScheduleListCollectionToTableDataArray(
        ObjectCollection $priceProductScheduleListCollection,
        array $priceProductScheduleList
    ): array {
        foreach ($priceProductScheduleListCollection as $priceProductScheduleListEntity) {
            $priceProductScheduleList[$priceProductScheduleListEntity->getIdPriceProductScheduleList()] = $this->mapPriceProductScheduleListEntityToTableDataArray($priceProductScheduleListEntity);
        }

        return $priceProductScheduleList;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList $priceProductScheduleListEntity
     *
     * @return array
     */
    protected function mapPriceProductScheduleListEntityToTableDataArray(
        SpyPriceProductScheduleList $priceProductScheduleListEntity
    ): array {
        $priceProductScheduleListTableDataArray = $priceProductScheduleListEntity->toArray();
        $priceProductScheduleListTableDataArray[static::COL_STATUS] = $this->prepareStatusField($priceProductScheduleListEntity);
        $priceProductScheduleListTableDataArray[static::COL_IMPORTED_BY] = $this->prepareImportedByField($priceProductScheduleListEntity);
        $priceProductScheduleListTableDataArray[static::COL_ACTIONS] = $this->buildLinks($priceProductScheduleListEntity);

        return $priceProductScheduleListTableDataArray;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList $priceProductScheduleListEntity
     *
     * @return string
     */
    protected function prepareStatusField(SpyPriceProductScheduleList $priceProductScheduleListEntity): string
    {
        return $priceProductScheduleListEntity->getIsActive() ? static::STATUS_PUBLISHED : static::STATUS_DRAFT;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList $priceProductScheduleListEntity
     *
     * @return string
     */
    protected function prepareImportedByField(SpyPriceProductScheduleList $priceProductScheduleListEntity): string
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $defaultStoreTimezone = new DateTimeZone($storeTransfer->getTimezone());
        $createdAt = $priceProductScheduleListEntity->getCreatedAt()
            ->setTimezone($defaultStoreTimezone)
            ->format(static::PATTERN_DATE_TIME);
        $userFullName = static::DEFAULT_IMPORTED_BY_VALUE;

        $userEntity = $priceProductScheduleListEntity->getUser();

        if ($userEntity !== null) {
            $userFullName = sprintf(
                static::TEMPLATE_FULL_NAME,
                $userEntity->getFirstName(),
                $userEntity->getLastName()
            );
        }

        return sprintf(static::TEMPLATE_IMPORTED_BY, $userFullName, $createdAt);
    }

    /**
     * @module User
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function prepareQuery(): ModelCriteria
    {
        return $this->priceProductScheduleListQuery
            ->joinUser()
            ->usePriceProductScheduleQuery()
                ->withColumn(
                    sprintf(static::EXPRESSION_COUNT, SpyPriceProductScheduleTableMap::COL_ID_PRICE_PRODUCT_SCHEDULE),
                    static::COL_NUMBER_OF_PRICES
                )
                ->withColumn(
                    sprintf(
                        static::EXPRESSION_DISTINCT_COUNT_ADDITION,
                        SpyPriceProductScheduleTableMap::COL_FK_PRODUCT,
                        SpyPriceProductScheduleTableMap::COL_FK_PRODUCT_ABSTRACT
                    ),
                    static::COL_NUMBER_OF_PRODUCTS
                )
            ->endUse()
            ->groupBy(SpyPriceProductScheduleListTableMap::COL_ID_PRICE_PRODUCT_SCHEDULE_LIST);
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList $priceProductScheduleListEntity
     *
     * @return string
     */
    protected function buildLinks(SpyPriceProductScheduleList $priceProductScheduleListEntity): string
    {
        $actionButtons = [
            $this->generatePriceProductScheduleListViewButton($priceProductScheduleListEntity),
            $this->generatePriceProductScheduleListEditButton($priceProductScheduleListEntity),
            $this->generatePriceProductScheduleListDownloadButton($priceProductScheduleListEntity),
            $this->generatePriceProductScheduleListDeleteButton($priceProductScheduleListEntity),
        ];

        return implode(' ', $actionButtons);
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList $priceProductScheduleListEntity
     *
     * @return string
     */
    protected function generatePriceProductScheduleListViewButton(
        SpyPriceProductScheduleList $priceProductScheduleListEntity
    ): string {
        if ($priceProductScheduleListEntity->getIsActive()) {
            return $this->generateViewButton(
                Url::generate(static::URL_PRICE_PRODUCT_SCHEDULE_LIST_VIEW, [
                    static::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListEntity->getIdPriceProductScheduleList(),
                ]),
                static::BUTTON_VIEW
            );
        }

        return $this->generateViewButton(
            Url::generate(static::URL_PRICE_PRODUCT_SCHEDULE_LIST_DRY_RUN_IMPORT, [
                static::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListEntity->getIdPriceProductScheduleList(),
            ]),
            static::BUTTON_VIEW
        );
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList $priceProductScheduleListEntity
     *
     * @return string
     */
    protected function generatePriceProductScheduleListEditButton(
        SpyPriceProductScheduleList $priceProductScheduleListEntity
    ): string {
        if ($priceProductScheduleListEntity->getIsActive()) {
            return $this->generateEditButton(
                Url::generate(static::URL_PRICE_PRODUCT_SCHEDULE_LIST_EDIT, [
                    static::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListEntity->getIdPriceProductScheduleList(),
                ]),
                static::BUTTON_EDIT
            );
        }

        return $this->generateEditButton(
            Url::generate(static::URL_PRICE_PRODUCT_SCHEDULE_LIST_DRY_RUN_IMPORT, [
                static::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListEntity->getIdPriceProductScheduleList(),
            ]),
            static::BUTTON_EDIT
        );
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList $priceProductScheduleListEntity
     *
     * @return string
     */
    protected function generatePriceProductScheduleListDownloadButton(
        SpyPriceProductScheduleList $priceProductScheduleListEntity
    ): string {
        return $this->generateViewButton(
            Url::generate(static::URL_PRICE_PRODUCT_SCHEDULE_LIST_DOWNLOAD, [
                static::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListEntity->getIdPriceProductScheduleList(),
            ]),
            static::BUTTON_DOWNLOAD
        );
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList $priceProductScheduleListEntity
     *
     * @return string
     */
    protected function generatePriceProductScheduleListDeleteButton(
        SpyPriceProductScheduleList $priceProductScheduleListEntity
    ): string {
        return $this->generateRemoveButton(
            Url::generate(static::URL_PRICE_PRODUCT_SCHEDULE_LIST_DELETE, [
                static::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListEntity->getIdPriceProductScheduleList(),
            ]),
            static::BUTTON_DELETE
        );
    }
}
