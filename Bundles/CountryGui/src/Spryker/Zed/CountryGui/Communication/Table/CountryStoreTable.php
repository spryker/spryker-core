<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui\Communication\Table;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

abstract class CountryStoreTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const PARAM_STORE_ID = 'store-id';

    /**
     * @uses \Orm\Zed\Country\Persistence\Map\SpyCountryStoreTableMap::COL_ID_LOCALE_STORE
     *
     * @var string
     */
    protected const COUNTRY_STORE_COLUMN_ID = 'spy_country_store.id_country_store';

    /**
     * @var string
     */
    public const COLUMN_ISO2_CODE = 'iso2_code';

    /**
     * @var string
     */
    protected const COLUMN_ISO3_CODE = 'iso3_code';

    /**
     * @var string
     */
    protected const COLUMN_NAME = 'name';

    /**
     * @var string
     */
    protected const COLUMN_TITLE_ISO2_CODE = 'ISO 2 Code';

    /**
     * @var string
     */
    protected const COLUMN_TITLE_ISO3_CODE = 'ISO 3 Code';

    /**
     * @var string
     */
    protected const COLUMN_TITLE_NAME = 'Country Name';

    /**
     * @uses \Spryker\Zed\CountryGui\Communication\Controller\IndexController
     *
     * @var string
     */
    protected $baseUrl = '/country-gui/index';

    /**
     * @var int|null
     */
    protected $idStore;

    /**
     * @var array<\Spryker\Zed\CountryGui\Communication\Expander\CountryStoreTableExpanderInterface>
     */
    protected $countryStoreTableExpanders;

    /**
     * @param int|null $idStore
     * @param array<\Spryker\Zed\CountryGui\Communication\Expander\CountryStoreTableExpanderInterface> $countryStoreTableExpanders
     */
    public function __construct(?int $idStore, array $countryStoreTableExpanders)
    {
        $this->idStore = $idStore;
        $this->countryStoreTableExpanders = $countryStoreTableExpanders;
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
            static::COLUMN_ISO2_CODE => static::COLUMN_TITLE_ISO2_CODE,
            static::COLUMN_ISO3_CODE => static::COLUMN_TITLE_ISO3_CODE,
        ]);

        $config->setSearchable([
            static::COLUMN_NAME,
            static::COLUMN_ISO2_CODE,
            static::COLUMN_ISO3_CODE,
        ]);

        $config->setSortable([
            static::COLUMN_NAME,
            static::COLUMN_ISO2_CODE => static::COLUMN_TITLE_ISO2_CODE,
            static::COLUMN_ISO3_CODE => static::COLUMN_TITLE_ISO3_CODE,
        ]);

        $config->setDefaultSortField(static::COLUMN_NAME);

        $config->setUrl($this->getUrl());

        foreach ($this->countryStoreTableExpanders as $countryStoreTableExpander) {
            $countryStoreTableExpander->expandConfiguration($config);
        }

        return $config;
    }

    /**
     * @return string
     */
    protected function getUrlPath(): string
    {
        $path = $this->getBaseUrlPath();

        foreach ($this->countryStoreTableExpanders as $countryStoreTableExpander) {
            $path = $countryStoreTableExpander->expandUrlPath($path);
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
            foreach ($this->countryStoreTableExpanders as $countryStoreTableExpander) {
                $preparedData[$key] = $countryStoreTableExpander->expandRow($row);
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
