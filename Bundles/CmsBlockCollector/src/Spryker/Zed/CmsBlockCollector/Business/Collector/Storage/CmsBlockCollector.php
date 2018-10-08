<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCollector\Business\Collector\Storage;

use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\CmsBlock\CmsBlockConfig;
use Spryker\Zed\CmsBlockCollector\Persistence\Collector\Storage\Propel\CmsBlockCollectorQuery;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;

class CmsBlockCollector extends AbstractStoragePropelCollector
{
    public const COL_IS_IN_STORE = 'is_in_store';

    /**
     * @var \Spryker\Zed\CmsBlockCollector\Dependency\Plugin\CmsBlockCollectorDataExpanderPluginInterface[]
     */
    protected $collectorDataExpanderPlugins = [];

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\CmsBlockCollector\Dependency\Plugin\CmsBlockCollectorDataExpanderPluginInterface[] $collectorDataExpanderPlugins
     */
    public function __construct(
        UtilDataReaderServiceInterface $utilDataReaderService,
        array $collectorDataExpanderPlugins = []
    ) {
        parent::__construct($utilDataReaderService);
        $this->collectorDataExpanderPlugins = $collectorDataExpanderPlugins;
    }

    /**
     * @param array $collectItemData
     *
     * @return bool
     */
    protected function isStorable(array $collectItemData)
    {
        return $collectItemData[static::COL_IS_IN_STORE] !== null;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $contentPlaceholders = $this->extractPlaceholders(
            $collectItemData[CmsBlockCollectorQuery::COL_PLACEHOLDERS],
            $collectItemData[CmsBlockCollectorQuery::COL_GLOSSARY_KEYS]
        );

        $baseCollectedCmsBlockData = [
            'id' => $collectItemData[CmsBlockCollectorQuery::COL_ID_CMS_BLOCK],
            'valid_from' => $collectItemData[CmsBlockCollectorQuery::COL_VALID_FROM],
            'valid_to' => $collectItemData[CmsBlockCollectorQuery::COL_VALID_TO],
            'is_active' => $collectItemData[CmsBlockCollectorQuery::COL_IS_ACTIVE],
            'template' => $collectItemData[CmsBlockCollectorQuery::COL_TEMPLATE_PATH],
            'placeholders' => $contentPlaceholders,
            'name' => $collectItemData[CmsBlockCollectorQuery::COL_NAME],
        ];

        return $this->runDataExpanderPlugins($baseCollectedCmsBlockData);
    }

    /**
     * @param array $collectedItemData
     *
     * @return array
     */
    protected function runDataExpanderPlugins(array $collectedItemData)
    {
        foreach ($this->collectorDataExpanderPlugins as $dataExpanderPlugin) {
            $collectedItemData = $dataExpanderPlugin->expand($collectedItemData, $this->locale);
        }

        return $collectedItemData;
    }

    /**
     * @param mixed $data
     * @param string $localeName
     * @param array $collectedItemData
     *
     * @return string
     */
    protected function collectKey($data, $localeName, array $collectedItemData)
    {
        return $this->generateKey($collectedItemData[CmsBlockCollectorQuery::COL_NAME], $localeName);
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return CmsBlockConfig::RESOURCE_TYPE_CMS_BLOCK;
    }

    /**
     * @param string $placeholders
     * @param string $glossaryKeys
     *
     * @return array
     */
    protected function extractPlaceholders($placeholders, $glossaryKeys)
    {
        $separator = ',';
        $placeholderNames = explode($separator, trim($placeholders));
        $glossaryKeys = explode($separator, trim($glossaryKeys));

        $step = 0;
        $placeholderCollection = [];
        foreach ($placeholderNames as $name) {
            $placeholderCollection[$name] = $glossaryKeys[$step];
            $step++;
        }

        return $placeholderCollection;
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }
}
