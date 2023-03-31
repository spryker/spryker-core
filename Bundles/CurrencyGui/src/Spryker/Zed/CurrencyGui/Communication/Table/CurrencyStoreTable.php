<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Communication\Table;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

abstract class CurrencyStoreTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const PARAM_STORE_ID = 'store-id';

    /**
     * @uses \Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap::COL_CODE
     *
     * @var string
     */
    protected const ENTITY_COLUMN_CODE = 'spy_currency.code';

    /**
     * @uses \Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap::COL_NAME
     *
     * @var string
     */
    protected const ENTITY_COLUMN_NAME = 'spy_currency.name';

    /**
     * @uses \Orm\Zed\Currency\Persistence\Map\SpyCurrencyStoreTableMap::COL_ID_LOCALE_STORE
     *
     * @var string
     */
    protected const CURRENCY_STORE_COLUMN_ID = 'spy_currency_store.id_currency_store';

    /**
     * @var string
     */
    protected const COLUMN_CODE = 'code';

    /**
     * @var string
     */
    protected const COLUMN_NAME = 'name';

    /**
     * @var string
     */
    protected const COLUMN_TITLE_CODE = 'Currency Code';

    /**
     * @var string
     */
    protected const COLUMN_TITLE_NAME = 'Currency Name';

    /**
     * @uses \Spryker\Zed\CurrencyGui\Communication\Controller\IndexController
     *
     * @var string
     */
    protected $baseUrl = '/currency-gui/index';

    /**
     * @var int|null
     */
    protected $idStore;

    /**
     * @var array<\Spryker\Zed\CurrencyGui\Communication\Expander\CurrencyStoreTableExpanderInterface>
     */
    protected $currencyStoreTableExpanders;

    /**
     * @param int|null $idStore
     * @param array<\Spryker\Zed\CurrencyGui\Communication\Expander\CurrencyStoreTableExpanderInterface> $currencyStoreTableExpanders
     */
    public function __construct(?int $idStore, array $currencyStoreTableExpanders)
    {
        $this->idStore = $idStore;
        $this->currencyStoreTableExpanders = $currencyStoreTableExpanders;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COLUMN_NAME => static::COLUMN_TITLE_NAME,
            static::COLUMN_CODE => static::COLUMN_TITLE_CODE,
        ]);

        $config->setSearchable([
            static::COLUMN_NAME,
            static::COLUMN_CODE,
        ]);

        $config->setSortable([
            static::COLUMN_NAME,
            static::COLUMN_CODE => static::COLUMN_TITLE_CODE,
        ]);

        $config->setDefaultSortField(static::COLUMN_NAME);

        $config->setUrl($this->getUrl());

        foreach ($this->currencyStoreTableExpanders as $currencyStoreTableExpander) {
            $currencyStoreTableExpander->expandConfiguration($config);
        }

        return $config;
    }

    /**
     * @return string
     */
    protected function getUrlPath(): string
    {
        $path = $this->getBaseUrlPath();

        foreach ($this->currencyStoreTableExpanders as $currencyStoreTableExpander) {
            $path = $currencyStoreTableExpander->expandUrlPath($path);
        }

        return $path;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<array<string>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $preparedData = $this->getRawData($config);

        foreach ($preparedData as $key => $row) {
            foreach ($this->currencyStoreTableExpanders as $currencyStoreTableExpander) {
                $preparedData[$key] = $currencyStoreTableExpander->expandRow($row);
            }
        }

        return $preparedData;
    }

    /**
     * @return string
     */
    abstract protected function getBaseUrlPath(): string;

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<array<string>>
     */
    abstract protected function getRawData(TableConfiguration $config): array;

    /**
     * @return string
     */
    protected function getUrl(): string
    {
        return Url::generate($this->getUrlPath(), [static::PARAM_STORE_ID => $this->idStore]);
    }
}
