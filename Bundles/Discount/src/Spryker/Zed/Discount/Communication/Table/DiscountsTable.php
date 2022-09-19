<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Table;

use Orm\Zed\Discount\Persistence\Map\SpyDiscountTableMap;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Discount\Communication\Form\DiscountVisibilityForm;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Traversable;

class DiscountsTable extends AbstractTable
{
    public const TABLE_COL_PERIOD = self::TYPE_COL_PERIOD;

    /**
     * @var string
     */
    public const TABLE_COL_TYPE = 'Type';

    /**
     * @var string
     */
    public const TYPE_COL_PERIOD = 'Period';

    /**
     * @var string
     */
    public const TABLE_COL_ACTIONS = 'Actions';

    /**
     * @var string
     */
    public const TABLE_COL_STORE = 'Store';

    /**
     * @var string
     */
    public const URL_PARAM_ID_DISCOUNT = 'id-discount';

    /**
     * @var string
     */
    public const URL_PARAM_VISIBILITY = 'visibility';

    /**
     * @var string
     */
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @var string
     */
    public const URL_FRAGMENT_TAB_CONTENT_VOUCHER = 'tab-content-voucher';

    /**
     * @var string
     */
    public const DATE_FORMAT = 'Y-m-d H:i';

    /**
     * @var string
     */
    public const BUTTON_ACTIVATE = 'Activate';

    /**
     * @var string
     */
    public const BUTTON_DEACTIVATE = 'Deactivate';

    /**
     * @var string
     */
    protected const TABLE_COL_PRIORITY = 'priority';

    /**
     * @var \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    protected $discountQuery;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @var array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface>
     */
    protected $calculatorPlugins = [];

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface
     */
    protected $discountRepository;

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountQuery $discountQuery
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     * @param array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface> $calculatorPlugins
     * @param \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface $discountRepository
     */
    public function __construct(
        SpyDiscountQuery $discountQuery,
        DiscountQueryContainerInterface $discountQueryContainer,
        array $calculatorPlugins,
        DiscountRepositoryInterface $discountRepository
    ) {
        $this->discountQuery = $discountQuery;
        $this->discountQueryContainer = $discountQueryContainer;
        $this->calculatorPlugins = $calculatorPlugins;
        $this->discountRepository = $discountRepository;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $url = Url::generate('list-table')->build();
        $config->setUrl($url);

        $config->setHeader([
            SpyDiscountTableMap::COL_ID_DISCOUNT => 'Discount ID',
            SpyDiscountTableMap::COL_DISPLAY_NAME => 'Name',
            static::TABLE_COL_TYPE => static::TABLE_COL_TYPE,
            static::TYPE_COL_PERIOD => static::TABLE_COL_PERIOD,
            SpyDiscountTableMap::COL_IS_ACTIVE => 'Status',
            SpyDiscountTableMap::COL_IS_EXCLUSIVE => 'Exclusive',
            static::TABLE_COL_STORE => static::TABLE_COL_STORE,
            static::TABLE_COL_ACTIONS => static::TABLE_COL_ACTIONS,
        ]);

        $config->setSearchable([
            SpyDiscountTableMap::COL_DISPLAY_NAME,
            SpyDiscountTableMap::COL_ID_DISCOUNT,
        ]);

        $config->setSortable([
            SpyDiscountTableMap::COL_ID_DISCOUNT,
            SpyDiscountTableMap::COL_DISPLAY_NAME,
            SpyDiscountTableMap::COL_IS_ACTIVE,
            SpyDiscountTableMap::COL_IS_EXCLUSIVE,
        ]);

        $config->setDefaultSortField(
            SpyDiscountTableMap::COL_ID_DISCOUNT,
            TableConfiguration::SORT_DESC,
        );

        $config->addRawColumn(static::TABLE_COL_ACTIONS);
        $config->addRawColumn(static::TABLE_COL_STORE);
        $config->addRawColumn(SpyDiscountTableMap::COL_IS_ACTIVE);

        if ($this->discountRepository->hasPriorityField()) {
            $config = $this->expandTableConfigurationWithPriorityColumn($config);
        }

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $result = [];

        /** @var array<\Orm\Zed\Discount\Persistence\SpyDiscount> $discountEntities */
        $discountEntities = $this->runQuery($this->discountQuery, $config, true);

        $hasPriorityField = $this->discountRepository->hasPriorityField();

        foreach ($discountEntities as $discountEntity) {
            $rowData = [
                SpyDiscountTableMap::COL_ID_DISCOUNT => $discountEntity->getIdDiscount(),
                SpyDiscountTableMap::COL_DISPLAY_NAME => $discountEntity->getDisplayName(),
                static::TABLE_COL_TYPE => $this->getDiscountType($discountEntity),
                static::TYPE_COL_PERIOD => $this->createTimePeriod($discountEntity),
                SpyDiscountTableMap::COL_IS_ACTIVE => $this->getStatus($discountEntity),
                SpyDiscountTableMap::COL_IS_EXCLUSIVE => $discountEntity->getIsExclusive(),
                static::TABLE_COL_ACTIONS => $this->getActionButtons($discountEntity),
                static::TABLE_COL_STORE => $this->getStoreNames($discountEntity->getIdDiscount()),
            ];

            if ($hasPriorityField) {
                $rowData[static::TABLE_COL_PRIORITY] = $this->formatInt((int)$discountEntity->getPriority());
            }

            $result[] = $rowData;
        }

        return $result;
    }

    /**
     * @param int $idDiscount
     *
     * @return string
     */
    protected function getStoreNames($idDiscount)
    {
        $discountStoreCollection = $this
            ->discountQueryContainer
            ->queryDiscountStoreWithStoresByFkDiscount($idDiscount)
            ->find();

        return $this->extractStoreNames($discountStoreCollection);
    }

    /**
     * @param \Traversable<\Orm\Zed\Discount\Persistence\SpyDiscountStore> $discountStoreEntityCollection
     *
     * @return string
     */
    protected function extractStoreNames(Traversable $discountStoreEntityCollection)
    {
        $storeNames = [];
        foreach ($discountStoreEntityCollection as $discountStoreEntity) {
            $storeNames[] = sprintf(
                '<span class="label label-info">%s</span>',
                $discountStoreEntity->getSpyStore()->getName(),
            );
        }

        return implode(' ', $storeNames);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function getActionButtons(SpyDiscount $discountEntity)
    {
        $buttons = [];
        $buttons[] = $this->createEditButton($discountEntity);
        $buttons[] = $this->createViewButton($discountEntity);
        $buttons[] = $this->createAddVoucherCodeButton($discountEntity);
        $buttons[] = $this->createToggleDiscountVisibilityButton($discountEntity);

        return implode(' ', $buttons);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function getStatus(SpyDiscount $discountEntity)
    {
        return $this->generateLabel(
            $discountEntity->getIsActive() ? 'Active' : 'Inactive',
            $discountEntity->getIsActive() ? 'label-info' : 'label-danger',
        );
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function getDiscountType(SpyDiscount $discountEntity)
    {
        return str_replace('_', ' ', $discountEntity->getDiscountType());
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function createEditButton(SpyDiscount $discountEntity)
    {
        $editDiscountUrl = Url::generate(
            '/discount/index/edit',
            [
                static::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount(),
            ],
        );

        return $this->generateEditButton($editDiscountUrl, 'Edit');
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function createViewButton(SpyDiscount $discountEntity)
    {
        $viewDiscountUrl = Url::generate(
            '/discount/index/view',
            [
                static::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount(),
            ],
        );

        return $this->generateViewButton($viewDiscountUrl, 'View');
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function createAddVoucherCodeButton(SpyDiscount $discountEntity)
    {
        if (!$discountEntity->getFkDiscountVoucherPool()) {
            return '';
        }

        $addVoucherCodeDiscountUrl = Url::generate(
            '/discount/index/edit',
            [
                static::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount(),
            ],
            [
                Url::FRAGMENT => static::URL_FRAGMENT_TAB_CONTENT_VOUCHER,
            ],
        );

        return $this->generateCreateButton($addVoucherCodeDiscountUrl, 'Add code');
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function createToggleDiscountVisibilityButton(SpyDiscount $discountEntity)
    {
        $visibility = static::BUTTON_ACTIVATE;
        if ($discountEntity->getIsActive()) {
            $visibility = static::BUTTON_DEACTIVATE;
        }

        $viewDiscountUrl = Url::generate(
            '/discount/index/toggle-discount-visibility',
            [
                static::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount(),
                static::URL_PARAM_VISIBILITY => $visibility,
                static::URL_PARAM_REDIRECT_URL => '/discount/index/list',
            ],
        );

        return $this->generateStatusButton($viewDiscountUrl, $visibility);
    }

    /**
     * @param \Spryker\Service\UtilText\Model\Url\Url $viewDiscountUrl
     * @param string $visibility
     *
     * @return string
     */
    protected function generateStatusButton(Url $viewDiscountUrl, $visibility)
    {
        if ($visibility === static::BUTTON_ACTIVATE) {
            return $this->generateFormButton($viewDiscountUrl, $visibility, DiscountVisibilityForm::class);
        }

        return $this->generateFormButton(
            $viewDiscountUrl,
            $visibility,
            DiscountVisibilityForm::class,
            [
                static::BUTTON_CLASS => 'btn-danger safe-submit',
                static::BUTTON_ICON => 'fa-trash',
            ],
        );
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function createTimePeriod(SpyDiscount $discountEntity)
    {
        /** @var string $validFrom */
        $validFrom = $discountEntity->getValidFrom(static::DATE_FORMAT);
        /** @var string $validTo */
        $validTo = $discountEntity->getValidTo(static::DATE_FORMAT);

        return $validFrom . ' UTC - ' . $validTo . ' UTC';
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function expandTableConfigurationWithPriorityColumn(TableConfiguration $config): TableConfiguration
    {
        $headers = $config->getHeader();
        $headers = $this->insertHeaderAfterColumn(
            $headers,
            SpyDiscountTableMap::COL_IS_ACTIVE,
            [static::TABLE_COL_PRIORITY => 'Priority'],
        );
        $config->setHeader($headers);

        $searchable = $config->getSearchable();
        $searchable[] = static::TABLE_COL_PRIORITY;
        $config->setSearchable($searchable);

        $sortable = $config->getSortable();
        $sortable[] = static::TABLE_COL_PRIORITY;
        $config->setSortable($sortable);

        return $config;
    }

    /**
     * @param array<string, mixed> $headers
     * @param string $afterColumnKey
     * @param array<string, mixed> $insertData
     *
     * @return array<string, mixed>
     */
    protected function insertHeaderAfterColumn(array $headers, string $afterColumnKey, array $insertData): array
    {
        $keys = array_keys($headers);
        $index = array_search($afterColumnKey, $keys, true);
        $pos = $index === false ? count($headers) : $index + 1;

        return array_merge(array_slice($headers, 0, $pos), $insertData, array_slice($headers, $pos));
    }
}
