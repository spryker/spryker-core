<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Table;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStore;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface;

class StoreTable extends AbstractTable
{
    /**
     * @var string
     */
    public const COL_ID_STORE = 'id_store';

    /**
     * @var string
     */
    protected const COL_NAME = 'name';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    protected const VALUE_COL_ID_STORE = 'Store ID';

    /**
     * @var string
     */
    protected const VALUE_COL_NAME = 'Name';

    /**
     * @var string
     */
    protected const VALUE_COL_ACTIONS = 'Actions';

    /**
     * @var string
     */
    protected const IDENTIFIER = 'store_data_table';

    /**
     * @var string
     */
    protected const BUTTON_LABEL_EDIT = 'Edit Store';

    /**
     * @var string
     */
    protected const BUTTON_LABEL_VIEW = 'View Store';

    /**
     * @uses \Spryker\Zed\StoreGui\Communication\Controller\EditStoreController::indexAction()
     *
     * @var string
     */
    protected const URL_STORE_EDIT = '/store-gui/edit';

    /**
     * @uses \Spryker\Zed\StoreGui\Communication\Controller\ViewStoreController::indexAction()
     *
     * @var string
     */
    protected const URL_STORE_VIEW = '/store-gui/view';

    /**
     * @var \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    protected SpyStoreQuery $storeQuery;

    /**
     * @var array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreTableExpanderPluginInterface>
     */
    protected array $tableExpanderPlugins;

    /**
     * @var \Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface
     */
    protected StoreGuiToStoreFacadeInterface $storeFacade;

    /**
     * @uses \Spryker\Zed\StoreGui\Communication\Controller\EditController::REQUEST_ID_STORE
     *
     * @var string
     */
    protected const REQUEST_ID_STORE = 'id-store';

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStoreQuery $storeQuery
     * @param array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreTableExpanderPluginInterface> $configExpanderPlugins
     * @param \Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        SpyStoreQuery $storeQuery,
        array $configExpanderPlugins,
        StoreGuiToStoreFacadeInterface $storeFacade
    ) {
        $this->storeQuery = $storeQuery;
        $this->tableExpanderPlugins = $configExpanderPlugins;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $this->setHeader($config);
        $this->setRawColumns($config);

        $config->setDefaultSortField(static::COL_NAME);

        $config->setSortable([
            static::COL_ID_STORE,
            static::COL_NAME,
        ]);

        $config->setSearchable([
            static::COL_ID_STORE,
            static::COL_NAME,
        ]);

        $this->setTableIdentifier(static::IDENTIFIER);

        foreach ($this->tableExpanderPlugins as $configExpanderPlugin) {
            $config = $configExpanderPlugin->expandConfig($config);
        }
        $this->addActionsHeader($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setHeader(TableConfiguration $config): void
    {
        $config->setHeader([
            static::COL_ID_STORE => static::VALUE_COL_ID_STORE,
            static::COL_NAME => static::VALUE_COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setRawColumns(TableConfiguration $config): void
    {
        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->storeQuery, $config, true);

        $storeCollection = [];
        $storeTransfers = [];

        /** @var \Orm\Zed\Store\Persistence\SpyStore $storeEntity */
        foreach ($queryResults as $storeEntity) {
            $storeTransfers[] = $this->mapStoreEntityToTransfer($storeEntity);
        }

        foreach ($this->tableExpanderPlugins as $prepareDataExpanderPlugin) {
            $storeTransfers = $prepareDataExpanderPlugin->expandStoreTransfers($storeTransfers);
        }

        foreach ($storeTransfers as $storeTransfer) {
            $item = $this->generateItem($storeTransfer);

            foreach ($this->tableExpanderPlugins as $displayDataExpanderPlugin) {
                $item = $displayDataExpanderPlugin->expandDataItem($item, $storeTransfer);
            }

            $storeCollection[] = $item;
        }

        return $storeCollection;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapStoreEntityToTransfer(SpyStore $storeEntity): StoreTransfer
    {
        return (new StoreTransfer())->fromArray($storeEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    protected function generateItem(StoreTransfer $storeTransfer): array
    {
        return [
            static::COL_ID_STORE => $storeTransfer->getIdStoreOrFail(),
            static::COL_NAME => $storeTransfer->getNameOrFail(),
            static::COL_ACTIONS => $this->buildLinks($storeTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string
     */
    protected function buildLinks(StoreTransfer $storeTransfer): string
    {
        $buttons = [];
        /* Required by infrastructure, exists only for BC with DMS OFF mode. */
        if ($this->storeFacade->isDynamicStoreEnabled()) {
            $buttons[] = $this->createViewButton($storeTransfer);
            $buttons[] = $this->createEditButton($storeTransfer);
        }

        return implode(' ', $buttons);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string
     */
    protected function createViewButton(StoreTransfer $storeTransfer): string
    {
        return $this->generateButton(
            Url::generate(static::URL_STORE_VIEW, [
                static::REQUEST_ID_STORE => $storeTransfer->getIdStoreOrFail(),
            ]),
            static::BUTTON_LABEL_VIEW,
            [
                'class' => 'btn-view',
                'icon' => 'fa-search',
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string
     */
    protected function createEditButton(StoreTransfer $storeTransfer): string
    {
        return $this->generateButton(
            Url::generate(static::URL_STORE_EDIT, [
                static::REQUEST_ID_STORE => $storeTransfer->getIdStoreOrFail(),
            ]),
            static::BUTTON_LABEL_EDIT,
            [
                'class' => 'btn-edit',
                'icon' => 'fa-pencil-square-o',
            ],
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function addActionsHeader(TableConfiguration $config): void
    {
        $config->setHeader($config->getHeader() + [static::COL_ACTIONS => static::VALUE_COL_ACTIONS]);
    }
}
