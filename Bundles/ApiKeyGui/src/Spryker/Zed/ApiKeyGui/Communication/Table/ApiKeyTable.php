<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyGui\Communication\Table;

use Orm\Zed\ApiKey\Persistence\SpyApiKey;
use Orm\Zed\ApiKey\Persistence\SpyApiKeyQuery;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ApiKeyGui\ApiKeyGuiConfig;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ApiKeyTable extends AbstractTable
{
    /**
     * @var string
     */
    public const COL_ID_API_KEY = 'id_api_key';

    /**
     * @var string
     */
    protected const VALUE_COL_ID_API_KEY = 'ID';

    /**
     * @var string
     */
    protected const COL_NAME = 'name';

    /**
     * @var string
     */
    protected const COL_CREATED_BY = 'created_by';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'Actions';

    /**
     * @var string
     */
    protected const VALUE_COL_NAME = 'Name';

    /**
     * @var string
     */
    protected const VALUE_COL_CREATED_BY = 'Created by';

    /**
     * @var string
     */
    protected const IDENTIFIER = 'api_key_data_table';

    /**
     * @var string
     */
    protected const USERNAME = 'username';

    /**
     * @var string
     */
    protected const URL_PARAM_ID = 'id';

    /**
     * @var string
     */
    protected const EDIT_BUTTON = 'Edit';

    /**
     * @var string
     */
    protected const DELETE_BUTTON = 'Delete';

    /**
     * @var \Orm\Zed\ApiKey\Persistence\SpyApiKeyQuery
     */
    protected SpyApiKeyQuery $apiKeyQuery;

    /**
     * @param \Orm\Zed\ApiKey\Persistence\SpyApiKeyQuery $apiKeyQuery
     */
    public function __construct(SpyApiKeyQuery $apiKeyQuery)
    {
        $this->apiKeyQuery = $apiKeyQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $this->setHeader($config);
        $this->setSortable($config);
        $this->setSearchable($config);
        $this->setTableIdentifier(static::IDENTIFIER);

        $config->setDefaultSortField(static::COL_NAME);
        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

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
            static::COL_ID_API_KEY => static::VALUE_COL_ID_API_KEY,
            static::COL_NAME => static::VALUE_COL_NAME,
            static::COL_CREATED_BY => static::VALUE_COL_CREATED_BY,
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSearchable(TableConfiguration $config): void
    {
        $config->setSearchable([
            static::COL_ID_API_KEY,
            static::COL_NAME,
            static::COL_CREATED_BY,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSortable(TableConfiguration $config): void
    {
        $config->setSortable([
            static::COL_ID_API_KEY,
            static::COL_NAME,
            static::COL_CREATED_BY,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $preparedQuery = $this->apiKeyQuery
            ->joinUser()
            ->withColumn(SpyUserTableMap::COL_USERNAME, static::USERNAME);
        $queryResults = $this->runQuery($preparedQuery, $config, true);

        $apiKeyCollection = [];

        /** @var \Orm\Zed\ApiKey\Persistence\SpyApiKey $apiKeyEntity */
        foreach ($queryResults as $apiKeyEntity) {
            $apiKeyCollection[] = $this->generateItem($apiKeyEntity);
        }

        return $apiKeyCollection;
    }

    /**
     * @param \Orm\Zed\ApiKey\Persistence\SpyApiKey $apiKeyEntity
     *
     * @return array<string, mixed>
     */
    protected function generateItem(SpyApiKey $apiKeyEntity): array
    {
        return [
            static::COL_ID_API_KEY => $this->formatInt($apiKeyEntity->getIdApiKey()),
            static::COL_NAME => $apiKeyEntity->getName(),
            static::COL_CREATED_BY => $apiKeyEntity->getVirtualColumns()[static::USERNAME],
            static::COL_ACTIONS => implode(' ', $this->createTableActions($apiKeyEntity->getIdApiKey())),
        ];
    }

    /**
     * @param int $idApiKey
     *
     * @return array
     */
    protected function createTableActions(int $idApiKey): array
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate(ApiKeyGuiConfig::URL_API_KEY_EDIT, [static::URL_PARAM_ID => $idApiKey]),
            static::EDIT_BUTTON,
        );

        $buttons[] = $this->generateRemoveButton(
            Url::generate(ApiKeyGuiConfig::URL_API_KEY_DELETE, [static::URL_PARAM_ID => $idApiKey]),
            static::DELETE_BUTTON,
        );

        return $buttons;
    }
}
