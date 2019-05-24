<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractStore;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ContentProductGui\Communication\Controller\ProductAbstractController;
use Spryker\Zed\ContentProductGui\Communication\Table\Manager\ProductAbstractTableManagerInterface;
use Spryker\Zed\ContentProductGui\ContentProductGuiConfig;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ProductAbstractSelectedTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'product-abstract-selected-table';
    public const TABLE_CLASS = 'product-abstract-selected-table gui-table-data';
    public const BASE_URL = '/content-product-gui/product-abstract/';

    public const COL_ID_PRODUCT_ABSTRACT = 'ID';
    public const COL_SKU = 'SKU';
    public const COL_IMAGE = 'Image';
    public const COL_NAME = 'Name';
    public const COL_STORES = 'Stores';
    public const COL_STATUS = 'Status';
    public const COL_ACTIONS = 'Actions';

    public const BUTTON_DELETE = 'Delete';
    public const BUTTON_MOVE_UP = 'Move Up';
    public const BUTTON_MOVE_DOWN = 'Move Down';

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ContentProductGui\Communication\Table\Manager\ProductAbstractTableManagerInterface
     */
    protected $productAbstractTableManager;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var string|null
     */
    protected $identifierSuffix;

    /**
     * @var array
     */
    protected $idProductAbstracts;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productQueryContainer
     * @param \Spryker\Zed\ContentProductGui\Communication\Table\Manager\ProductAbstractTableManagerInterface $productAbstractTableManager
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $identifierSuffix
     * @param array $idProductAbstracts
     */
    public function __construct(
        SpyProductAbstractQuery $productQueryContainer,
        ProductAbstractTableManagerInterface $productAbstractTableManager,
        LocaleTransfer $localeTransfer,
        ?string $identifierSuffix,
        array $idProductAbstracts
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productAbstractTableManager = $productAbstractTableManager;
        $this->localeTransfer = $localeTransfer;
        $this->identifierSuffix = $identifierSuffix;
        $this->idProductAbstracts = $idProductAbstracts;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $parameters = [];

        if ($this->idProductAbstracts) {
            $parameters = [ProductAbstractController::PARAM_IDS => $this->idProductAbstracts];
        }

        $this->baseUrl = static::BASE_URL;
        $this->defaultUrl = Url::generate(static::TABLE_IDENTIFIER, $parameters)->build();
        $this->tableClass = static::TABLE_CLASS;
        $identifierSuffix = !$this->identifierSuffix ?
            static::TABLE_IDENTIFIER :
            sprintf('%s-%s', static::TABLE_IDENTIFIER, $this->identifierSuffix);
        $this->setTableIdentifier($identifierSuffix);

        $this->disableSearch();

        $config->setHeader([
            static::COL_ID_PRODUCT_ABSTRACT => static::COL_ID_PRODUCT_ABSTRACT,
            static::COL_SKU => static::COL_SKU,
            static::COL_IMAGE => static::COL_IMAGE,
            static::COL_NAME => static::COL_NAME,
            static::COL_STORES => static::COL_STORES,
            static::COL_STATUS => static::COL_STATUS,
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);

        $config->setRawColumns([
            static::COL_IMAGE,
            static::COL_STORES,
            static::COL_STATUS,
            static::COL_ACTIONS,
        ]);

        $config->setStateSave(false);

        return $config;
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function newTableConfiguration(): TableConfiguration
    {
        $tableConfiguration = parent::newTableConfiguration();
        $tableConfiguration->setServerSide(false);
        $tableConfiguration->setPaging(false);
        $tableConfiguration->setOrdering(false);

        return $tableConfiguration;
    }

    /**
     * @module Product
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $results = [];
        if (!$this->idProductAbstracts) {
            return $results;
        }

        $idProductAbstracts = array_values($this->idProductAbstracts);
        $query = $this->productQueryContainer
            ->filterByIdProductAbstract_In($idProductAbstracts)
            ->useSpyProductAbstractLocalizedAttributesQuery()
            ->filterByFkLocale($this->localeTransfer->getIdLocale())
            ->endUse();

        $this->setLimit(ContentProductGuiConfig::MAX_NUMBER_PRODUCTS_IN_PRODUCT_ABSTRACT_LIST);
        $queryResults = $this->runQuery($query, $config, true);

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity */
        foreach ($queryResults as $productAbstractEntity) {
            $index = array_search($productAbstractEntity->getIdProductAbstract(), $idProductAbstracts);
            $results[$index] = $this->formatRow($productAbstractEntity);
        }
        ksort($results);

        return $results;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return array
     */
    protected function formatRow(SpyProductAbstract $productAbstractEntity): array
    {
        $idProductAbstract = $productAbstractEntity->getIdProductAbstract();

        return [
            static::COL_ID_PRODUCT_ABSTRACT => $idProductAbstract,
            static::COL_SKU => $productAbstractEntity->getSku(),
            static::COL_IMAGE => $this->getProductPreview($this->productAbstractTableManager->getProductPreviewUrl($productAbstractEntity)),
            static::COL_NAME => $productAbstractEntity->getSpyProductAbstractLocalizedAttributess()->getFirst()->getName(),
            static::COL_STORES => $this->getStoreNames($productAbstractEntity->getSpyProductAbstractStores()->getArrayCopy()),
            static::COL_STATUS => $this->getStatusLabel($this->productAbstractTableManager->getAbstractProductStatus($productAbstractEntity)),
            static::COL_ACTIONS => $this->getActionButtons($productAbstractEntity->getIdProductAbstract()),
        ];
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function getActionButtons(int $idProductAbstract): string
    {
        $actionButtons = [];
        $actionButtons[] = $this->generateButton(
            '#',
            static::BUTTON_DELETE,
            [
                'class' => 'js-delete-product-abstract btn-danger',
                'data-id' => $idProductAbstract,
                'icon' => 'fa-trash',
                'onclick' => 'return false;',
            ]
        );
        $actionButtons[] = $this->generateButton(
            '#',
            static::BUTTON_MOVE_UP,
            [
                'class' => 'js-reorder-product-abstract btn-create',
                'data-id' => $idProductAbstract,
                'data-direction' => 'up',
                'icon' => 'fa-arrow-up',
                'onclick' => 'return false;',
            ]
        );
        $actionButtons[] = $this->generateButton(
            '#',
            static::BUTTON_MOVE_DOWN,
            [
                'class' => 'js-reorder-product-abstract btn-create',
                'data-id' => $idProductAbstract,
                'data-direction' => 'down',
                'icon' => 'fa-arrow-down',
                'onclick' => 'return false;',
            ]
        );

        return implode(' ', $actionButtons);
    }

    /**
     * @param string $link
     *
     * @return string
     */
    protected function getProductPreview(string $link): string
    {
        if ($link) {
            return sprintf('<img src="%s">', $link);
        }

        return '';
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractStore[] $productAbstractStoreEntities
     *
     * @return string
     */
    protected function getStoreNames(array $productAbstractStoreEntities): string
    {
        return array_reduce(
            $productAbstractStoreEntities,
            function (string $accumulator, SpyProductAbstractStore $productAbstractStoreEntity): string {
                return $accumulator . " " . $this->generateLabel($productAbstractStoreEntity->getSpyStore()->getName(), 'label-info');
            },
            ""
        );
    }

    /**
     * @param bool $status
     *
     * @return string
     */
    protected function getStatusLabel(bool $status): string
    {
        if (!$status) {
            return $this->generateLabel('Inactive', 'label-danger');
        }

        return $this->generateLabel('Active', 'label-info');
    }
}
