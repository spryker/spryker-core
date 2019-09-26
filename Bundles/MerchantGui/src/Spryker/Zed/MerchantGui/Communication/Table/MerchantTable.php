<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Table;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantGui\Communication\Table\PluginExecutor\MerchantTablePluginExecutorInterface;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;

class MerchantTable extends AbstractTable
{
    protected const STATUS_CLASS_LABEL_MAPPING = [
        'waiting-for-approval' => 'label-warning',
        'approved' => 'label-info',
        'denied' => 'label-danger',
    ];

    protected const STATUS_CLASS_BUTTON_MAPPING = [
        'approved' => 'btn-create',
        'denied' => 'btn-remove',
    ];

    /**
     * @var \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected $merchantQuery;

    /**
     * @var \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantGui\Communication\Table\PluginExecutor\MerchantTablePluginExecutorInterface
     */
    protected $merchantTablePluginExecutor;

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchantQuery $merchantQuery
     * @param \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantGui\Communication\Table\PluginExecutor\MerchantTablePluginExecutorInterface $merchantTablePluginExecutor
     */
    public function __construct(
        SpyMerchantQuery $merchantQuery,
        MerchantGuiToMerchantFacadeInterface $merchantFacade,
        MerchantTablePluginExecutorInterface $merchantTablePluginExecutor
    ) {
        $this->merchantQuery = $merchantQuery;
        $this->merchantFacade = $merchantFacade;
        $this->merchantTablePluginExecutor = $merchantTablePluginExecutor;
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
            MerchantTableConstants::COL_ID_MERCHANT,
            MerchantTableConstants::COL_NAME,
            MerchantTableConstants::COL_STATUS,
        ]);

        $config->setRawColumns([
            MerchantTableConstants::COL_ACTIONS,
            MerchantTableConstants::COL_STATUS,
        ]);
        $config->setDefaultSortField(MerchantTableConstants::COL_ID_MERCHANT, TableConfiguration::SORT_DESC);

        $config->setSearchable([
            MerchantTableConstants::COL_ID_MERCHANT,
            MerchantTableConstants::COL_NAME,
            MerchantTableConstants::COL_STATUS,
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
        return $this->merchantTablePluginExecutor->executeConfigExpanderPlugins($tableConfiguration);
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function executeDataExpanderPlugins(array $item): array
    {
        return $this->merchantTablePluginExecutor->executeDataExpanderPlugins($item);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            MerchantTableConstants::COL_ID_MERCHANT => 'Merchant Id',
            MerchantTableConstants::COL_NAME => 'Name',
            MerchantTableConstants::COL_STATUS => 'Status',
        ];
        $externalData = $this->merchantTablePluginExecutor->executeTableHeaderExpanderPlugins();

        $actions = [MerchantTableConstants::COL_ACTIONS => 'Actions'];

        $config->setHeader($baseData + $externalData + $actions);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->merchantQuery, $config);
        $results = [];

        foreach ($queryResults as $item) {
            $rowData = array_merge([
                MerchantTableConstants::COL_ID_MERCHANT => $item[SpyMerchantTableMap::COL_ID_MERCHANT],
                MerchantTableConstants::COL_NAME => $item[SpyMerchantTableMap::COL_NAME],
                MerchantTableConstants::COL_STATUS => $this->createStatusLabel($item),
            ], $this->executeDataExpanderPlugins($item));
            $rowData[MerchantTableConstants::COL_ACTIONS] = $this->buildLinks($item);
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
            Url::generate(MerchantTableConstants::URL_MERCHANT_EDIT, [MerchantTableConstants::REQUEST_ID_MERCHANT => $item[MerchantTableConstants::COL_ID_MERCHANT]]),
            'Edit'
        );

        $buttons = array_merge(
            $buttons,
            $this->generateMerchantTableExpanderPluginsActionButtons($item),
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
        $availableStatuses = $this->merchantFacade->getApplicableMerchantStatuses($item[MerchantTableConstants::COL_STATUS]);
        foreach ($availableStatuses as $availableStatus) {
            $availableStatusButtons[] = $this->generateButton(
                Url::generate(
                    MerchantTableConstants::URL_MERCHANT_STATUS,
                    [MerchantTableConstants::REQUEST_ID_MERCHANT => $item[MerchantTableConstants::COL_ID_MERCHANT], 'status' => $availableStatus]
                ),
                $availableStatus . '_button',
                ['icon' => 'fa fa-key', 'class' => static::STATUS_CLASS_BUTTON_MAPPING[$availableStatus]]
            );
        }

        return $availableStatusButtons;
    }

    /**
     * @param array $item
     *
     * @return string[]
     */
    protected function generateMerchantTableExpanderPluginsActionButtons(array $item): array
    {
        $buttonTransfers = $this->merchantTablePluginExecutor->executeActionButtonExpanderPlugins($item);

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
}
