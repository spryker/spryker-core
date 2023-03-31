<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Table;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

abstract class LocaleStoreTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const PARAM_STORE_ID = 'store-id';

    /**
     * @uses \Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap::COL_LOCALE_NAME
     *
     * @var string
     */
    protected const ENTITY_COLUMN_NAME = 'spy_locale.locale_name';

    /**
     * @uses \Orm\Zed\Locale\Persistence\Map\SpyLocaleStoreTableMap::COL_ID_LOCALE_STORE
     *
     * @var string
     */
    protected const LOCALE_STORE_COLUMN_ID = 'spy_locale_store.id_locale_store';

    /**
     * @var string
     */
    protected const COLUMN_NAME = 'locale_name';

    /**
     * @var string
     */
    protected const COLUMN_TITLE_NAME = 'Locale Name';

    /**
     * @uses \Spryker\Zed\LocaleGui\Communication\Controller\IndexController
     *
     * @var string
     */
    protected $baseUrl = '/locale-gui/index';

    /**
     * @var int|null
     */
    protected $idStore;

    /**
     * @var array<\Spryker\Zed\LocaleGui\Communication\Expander\LocaleStoreTableExpanderInterface>
     */
    protected $localeStoreTableExpanders;

    /**
     * @param int|null $idStore
     * @param array<\Spryker\Zed\LocaleGui\Communication\Expander\LocaleStoreTableExpanderInterface> $localeStoreTableExpanders
     */
    public function __construct(?int $idStore, array $localeStoreTableExpanders)
    {
        $this->idStore = $idStore;
        $this->localeStoreTableExpanders = $localeStoreTableExpanders;
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
        ]);

        $config->setSearchable([
            static::COLUMN_NAME,
        ]);

        $config->setSortable([
            static::COLUMN_NAME,
        ]);

        $config->setDefaultSortField(static::COLUMN_NAME);

        $config->setUrl($this->getUrl());

        foreach ($this->localeStoreTableExpanders as $localeStoreTableExpander) {
            $localeStoreTableExpander->expandConfiguration($config);
        }

        return $config;
    }

    /**
     * @return string
     */
    protected function getUrlPath(): string
    {
        $path = $this->getBaseUrlPath();

        foreach ($this->localeStoreTableExpanders as $localeStoreTableExpander) {
            $path = $localeStoreTableExpander->expandUrlPath($path);
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
            foreach ($this->localeStoreTableExpanders as $localeStoreTableExpander) {
                $preparedData[$key] = $localeStoreTableExpander->expandRow($row);
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
