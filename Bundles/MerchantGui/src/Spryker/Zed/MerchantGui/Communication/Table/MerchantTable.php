<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Table;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantStoreTableMap;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantGui\Communication\Controller\EditMerchantController;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantStatusForm;
use Spryker\Zed\MerchantGui\Communication\Form\ToggleActiveMerchantForm;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantGui\MerchantGuiConfig;

class MerchantTable extends AbstractTable
{
    protected const REQUEST_ID_MERCHANT = 'id-merchant';

    public const COL_ACTIONS = 'actions';
    public const COL_STORES = 'stores';

    protected const STATUS_CLASS_LABEL_MAPPING = [
        MerchantGuiConfig::STATUS_WAITING_FOR_APPROVAL => 'label-warning',
        MerchantGuiConfig::STATUS_APPROVED => 'label-info',
        MerchantGuiConfig::STATUS_DENIED => 'label-danger',
    ];

    protected const STATUS_CLASS_BUTTON_MAPPING = [
        MerchantGuiConfig::STATUS_APPROVED => 'btn-create',
        MerchantGuiConfig::STATUS_DENIED => 'btn-remove',
    ];

    protected const STORE_CLASS_LABEL = 'label-info';

    /**
     * @var \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected $merchantQuery;

    /**
     * @var \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableActionExpanderPluginInterface[]
     */
    protected $merchantTableActionExpanderPlugins;

    /**
     * @var \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableHeaderExpanderPluginInterface[]
     */
    protected $merchantTableHeaderExpanderPlugins;

    /**
     * @var \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableDataExpanderPluginInterface[]
     */
    protected $merchantTableDataExpanderPlugins;

    /**
     * @var \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableConfigExpanderPluginInterface[]
     */
    protected $merchantTableConfigExpanderPlugins;

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchantQuery $merchantQuery
     * @param \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableActionExpanderPluginInterface[] $merchantTableActionExpanderPlugins
     * @param \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableHeaderExpanderPluginInterface[] $merchantTableHeaderExpanderPlugins
     * @param \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableDataExpanderPluginInterface[] $merchantTableDataExpanderPlugins
     * @param \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableConfigExpanderPluginInterface[] $merchantTableConfigExpanderPlugins
     */
    public function __construct(
        SpyMerchantQuery $merchantQuery,
        MerchantGuiToMerchantFacadeInterface $merchantFacade,
        array $merchantTableActionExpanderPlugins,
        array $merchantTableHeaderExpanderPlugins,
        array $merchantTableDataExpanderPlugins,
        array $merchantTableConfigExpanderPlugins
    ) {
        $this->merchantQuery = $merchantQuery;
        $this->merchantFacade = $merchantFacade;
        $this->merchantTableActionExpanderPlugins = $merchantTableActionExpanderPlugins;
        $this->merchantTableHeaderExpanderPlugins = $merchantTableHeaderExpanderPlugins;
        $this->merchantTableDataExpanderPlugins = $merchantTableDataExpanderPlugins;
        $this->merchantTableConfigExpanderPlugins = $merchantTableConfigExpanderPlugins;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);

        $config->setSortable([
            SpyMerchantTableMap::COL_ID_MERCHANT,
            SpyMerchantTableMap::COL_NAME,
            SpyMerchantTableMap::COL_STATUS,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
            SpyMerchantTableMap::COL_STATUS,
            SpyMerchantTableMap::COL_IS_ACTIVE,
            SpyMerchantStoreTableMap::COL_FK_STORE,
        ]);
        $config->setDefaultSortField(SpyMerchantTableMap::COL_ID_MERCHANT, TableConfiguration::SORT_DESC);

        $config->setSearchable([
            SpyMerchantTableMap::COL_ID_MERCHANT,
            SpyMerchantTableMap::COL_NAME,
            SpyMerchantTableMap::COL_STATUS,
        ]);

        $config = $this->executeConfigExpanderPlugins($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $tableConfiguration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function executeConfigExpanderPlugins(TableConfiguration $tableConfiguration): TableConfiguration
    {
        foreach ($this->merchantTableConfigExpanderPlugins as $merchantTableConfigExpanderPlugin) {
            $tableConfiguration = $merchantTableConfigExpanderPlugin->expand($tableConfiguration);
        }

        return $tableConfiguration;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function executeDataExpanderPlugins(array $item): array
    {
        $data = [];
        foreach ($this->merchantTableDataExpanderPlugins as $merchantTableDataExpanderPlugin) {
            $data[] = $merchantTableDataExpanderPlugin->expand($item);
        }

        return array_merge([], ...$data);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            SpyMerchantTableMap::COL_ID_MERCHANT => 'Merchant Id',
            SpyMerchantTableMap::COL_NAME => 'Name',
            SpyMerchantTableMap::COL_STATUS => 'Status',
            SpyMerchantTableMap::COL_IS_ACTIVE => 'active',
            SpyMerchantStoreTableMap::COL_FK_STORE => 'Stores',
        ];
        $externalData = $this->executeTableHeaderExpanderPlugins();

        $actions = [static::COL_ACTIONS => 'Actions'];

        $config->setHeader(array_merge($baseData, $externalData, $actions));

        return $config;
    }

    /**
     * @return array
     */
    protected function executeTableHeaderExpanderPlugins(): array
    {
        $expandedData = [];
        foreach ($this->merchantTableHeaderExpanderPlugins as $plugin) {
            $expandedData[] = $plugin->expand();
        }

        return array_merge([], ...$expandedData);
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function prepareQuery(): SpyMerchantQuery
    {
        $this->merchantQuery
            ->groupByIdMerchant()
            ->useSpyMerchantStoreQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinSpyStore()
                ->withColumn(
                    sprintf('GROUP_CONCAT(%s)', SpyStoreTableMap::COL_NAME),
                    static::COL_STORES
                )
            ->endUse();

        return $this->merchantQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);
        $results = [];

        foreach ($queryResults as $item) {
            $rowData = array_merge([
                SpyMerchantTableMap::COL_ID_MERCHANT => $item[SpyMerchantTableMap::COL_ID_MERCHANT],
                SpyMerchantTableMap::COL_NAME => $item[SpyMerchantTableMap::COL_NAME],
                SpyMerchantTableMap::COL_STATUS => $this->createStatusLabel($item),
                SpyMerchantTableMap::COL_IS_ACTIVE => $this->getActiveLabel($item[SpyMerchantTableMap::COL_IS_ACTIVE]),
                SpyMerchantStoreTableMap::COL_FK_STORE => $this->createStoresLabel($item),
            ], $this->executeDataExpanderPlugins($item));
            $rowData[static::COL_ACTIONS] = $this->buildLinks($item);
            $results[] = $rowData;
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item): string
    {
        $buttons = [];
        $buttons[] = $this->generateEditButton(
            Url::generate(MerchantGuiConfig::URL_MERCHANT_EDIT, [EditMerchantController::REQUEST_ID_MERCHANT => $item[SpyMerchantTableMap::COL_ID_MERCHANT]]),
            'Edit'
        );
        $buttons[] = ($item[SpyMerchantTableMap::COL_IS_ACTIVE]) ?
            $this->createDeactivateButton($item[SpyMerchantTableMap::COL_ID_MERCHANT]) :
            $this->createActivateButton($item[SpyMerchantTableMap::COL_ID_MERCHANT]);

        $buttons = array_merge(
            $buttons,
            $this->generateMerchantTableActionButtons($item),
            $this->buildAvailableStatusButtons($item)
        );

        return implode(' ', $buttons);
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function buildAvailableStatusButtons(array $item): array
    {
        $availableStatusButtons = [];
        $availableStatuses = $this->merchantFacade->getApplicableMerchantStatuses($item[SpyMerchantTableMap::COL_STATUS]);
        foreach ($availableStatuses as $availableStatus) {
            $availableStatusButtons[] = $this->generateFormButton(
                Url::generate(
                    MerchantGuiConfig::URL_MERCHANT_STATUS,
                    [EditMerchantController::REQUEST_ID_MERCHANT => $item[SpyMerchantTableMap::COL_ID_MERCHANT], 'status' => $availableStatus]
                ),
                $availableStatus . '_button',
                MerchantStatusForm::class,
                ['icon' => 'fa fa-key', 'class' => static::STATUS_CLASS_BUTTON_MAPPING[$availableStatus]]
            );
        }

        return $availableStatusButtons;
    }

    /**
     * @param int $idMerchant
     *
     * @return string
     */
    protected function createActivateButton(int $idMerchant): string
    {
        return $this->generateFormButton(
            Url::generate(
                MerchantGuiConfig::URL_MERCHANT_ACTIVATE,
                [EditMerchantController::REQUEST_ID_MERCHANT => $idMerchant]
            ),
            'Activate',
            ToggleActiveMerchantForm::class,
            [
                'class' => 'btn-view',
                'icon' => 'fa fa-caret-right',
            ]
        );
    }

    /**
     * @param int $idMerchant
     *
     * @return string
     */
    protected function createDeactivateButton(int $idMerchant): string
    {
        return $this->generateFormButton(
            Url::generate(
                MerchantGuiConfig::URL_MERCHANT_DEACTIVATE,
                [EditMerchantController::REQUEST_ID_MERCHANT => $idMerchant]
            ),
            'Deactivate',
            ToggleActiveMerchantForm::class,
            [
                    'class' => 'btn-remove',
                    'icon' => 'fa fa-trash',
            ]
        );
    }

    /**
     * @param array $item
     *
     * @return string[]
     */
    protected function generateMerchantTableActionButtons(array $item): array
    {
        $buttonTransfers = $this->executeActionButtonExpanderPlugins($item);

        $actionButtons = [];
        foreach ($buttonTransfers as $buttonTransfer) {
            $actionButtons[] = $this->generateButton(
                $buttonTransfer->getUrl(),
                $buttonTransfer->getTitle(),
                $buttonTransfer->getDefaultOptions(),
                $buttonTransfer->getCustomOptions()
            );
        }

        return $actionButtons;
    }

    /**
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    protected function executeActionButtonExpanderPlugins(array $item): array
    {
        $buttonTransfers = [];
        foreach ($this->merchantTableActionExpanderPlugins as $merchantsTableExpanderPlugin) {
            $buttonTransfers[] = $merchantsTableExpanderPlugin->expand($item);
        }

        return array_merge([], ...$buttonTransfers);
    }

    /**
     * @param array $merchant
     *
     * @return string
     */
    protected function createStatusLabel(array $merchant): string
    {
        $currentStatus = $merchant[SpyMerchantTableMap::COL_STATUS];

        if (!isset(static::STATUS_CLASS_LABEL_MAPPING[$currentStatus])) {
            return '';
        }

        return $this->generateLabel($currentStatus, static::STATUS_CLASS_LABEL_MAPPING[$currentStatus]);
    }

    /**
     * @param bool $isActive
     *
     * @return string
     */
    public function getActiveLabel(bool $isActive): string
    {
        return $isActive ? $this->generateLabel('Active', 'label-info') : $this->generateLabel('Inactive', 'label-danger');
    }

    /**
     * @param array $merchant
     *
     * @return string
     */
    protected function createStoresLabel(array $merchant): string
    {
        $storeNames = explode(',', $merchant[static::COL_STORES]);

        $storeLabels = [];
        foreach ($storeNames as $storeName) {
            $storeLabels[] = $this->generateLabel($storeName, static::STORE_CLASS_LABEL);
        }

        return implode(' ', $storeLabels);
    }
}
