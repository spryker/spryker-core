<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Config;

use Generated\Shared\Transfer\SearchConfigExtensionTransfer;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\SearchExtension\Config\SortConfigInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigPluginInterface;

class SortConfig implements SortConfigInterface
{
    public const DIRECTION_ASC = 'asc';
    public const DIRECTION_DESC = 'desc';

    public const DEFAULT_SORT_PARAM_KEY = 'sort';

    /**
     * @var \Generated\Shared\Transfer\SortConfigTransfer[]
     */
    protected $sortConfigTransfers = [];

    /**
     * @var string
     */
    protected $sortParamKey;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigPluginInterface|null $sortSearchConfigBuilderPlugin
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[] $searchConfigExpanderPlugins
     * @param string $sortParamName
     */
    public function __construct(
        ?SortConfigPluginInterface $sortSearchConfigBuilderPlugin = null,
        array $searchConfigExpanderPlugins = [],
        string $sortParamName = self::DEFAULT_SORT_PARAM_KEY
    ) {
        $this->sortParamKey = $sortParamName;

        $this->buildSortConfig($sortSearchConfigBuilderPlugin);
        $this->expandSortConfig($searchConfigExpanderPlugins);
    }

    /**
     * @param \Generated\Shared\Transfer\SortConfigTransfer $sortConfigTransfer
     *
     * @return $this
     */
    public function addSort(SortConfigTransfer $sortConfigTransfer)
    {
        $this->assertSortConfigTransfer($sortConfigTransfer);

        $this->sortConfigTransfers[$sortConfigTransfer->getParameterName()] = $sortConfigTransfer;

        return $this;
    }

    /**
     * @param string $parameterName
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer|null
     */
    public function get($parameterName): ?SortConfigTransfer
    {
        if (isset($this->sortConfigTransfers[$parameterName])) {
            return $this->sortConfigTransfers[$parameterName];
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\SortConfigTransfer[]
     */
    public function getAll(): array
    {
        return $this->sortConfigTransfers;
    }

    /**
     * @param array $requestParameters
     *
     * @return string|null
     */
    public function getActiveParamName(array $requestParameters): ?string
    {
        $sortParamName = array_key_exists($this->sortParamKey, $requestParameters) ? $requestParameters[$this->sortParamKey] : null;

        return $sortParamName;
    }

    /**
     * @param string $sortParamName
     *
     * @return string|null
     */
    public function getSortDirection($sortParamName): ?string
    {
        $sortConfigTransfer = $this->get($sortParamName);

        if (!$sortConfigTransfer) {
            return null;
        }

        if ($sortConfigTransfer->getIsDescending()) {
            return static::DIRECTION_DESC;
        }

        return static::DIRECTION_ASC;
    }

    /**
     * @param \Generated\Shared\Transfer\SortConfigTransfer $sortConfigTransfer
     *
     * @return void
     */
    protected function assertSortConfigTransfer(SortConfigTransfer $sortConfigTransfer): void
    {
        $sortConfigTransfer
            ->requireName()
            ->requireParameterName()
            ->requireFieldName();
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigPluginInterface|null $sortSearchConfigBuilderPlugin
     *
     * @return void
     */
    protected function buildSortConfig(?SortConfigPluginInterface $sortSearchConfigBuilderPlugin): void
    {
        if (!$sortSearchConfigBuilderPlugin) {
            return;
        }

        $sortSearchConfigBuilderPlugin->buildSortConfig($this);
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[] $searchConfigExpanderPlugins
     *
     * @return void
     */
    protected function expandSortConfig(array $searchConfigExpanderPlugins): void
    {
        foreach ($searchConfigExpanderPlugins as $searchConfigExpanderPlugin) {
            $searchConfigExtensionTransfer = $searchConfigExpanderPlugin->getSearchConfigExtension();
            $this->addSortConfigToSortConfigBuilder($searchConfigExtensionTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigExtensionTransfer $searchConfigExtensionTransfer
     *
     * @return void
     */
    protected function addSortConfigToSortConfigBuilder(SearchConfigExtensionTransfer $searchConfigExtensionTransfer): void
    {
        foreach ($searchConfigExtensionTransfer->getSortConfigs() as $sortConfigTransfer) {
            $this->addSort($sortConfigTransfer);
        }
    }
}
