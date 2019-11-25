<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\DataMapper\Page;

use Exception;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\CategoryMapTransfer;
use Generated\Shared\Transfer\DataMappingContextTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\SearchElasticsearch\Business\DataMapper\DataMapperInterface;
use Spryker\Zed\SearchElasticsearch\Business\Exception\PageMapPluginNotFoundException;
use Spryker\Zed\SearchElasticsearchExtension\Business\DataMapper\PageMapBuilderInterface;
use Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\PageMapPluginInterface;
use Zend\Filter\Word\UnderscoreToDash;

class PageDataMapper implements DataMapperInterface
{
    public const FACET_NAME = 'facet-name';
    public const FACET_VALUE = 'facet-value';
    public const ALL_PARENTS = 'all-parents';
    public const DIRECT_PARENTS = 'direct-parents';

    /**
     * @var \Spryker\Zed\SearchElasticsearchExtension\Business\DataMapper\PageMapBuilderInterface
     */
    protected $pageMapBuilder;

    /**
     * @var \Zend\Filter\Word\UnderscoreToDash
     */
    protected $underscoreToDashFilter;

    /**
     * @var \Generated\Shared\Search\PageIndexMap
     */
    protected $pageIndexMap;

    /**
     * @var \Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\PageMapPluginInterface[]
     */
    protected $pageMapPlugins = [];

    /**
     * @param \Spryker\Zed\SearchElasticsearchExtension\Business\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param \Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\PageMapPluginInterface[] $pageMapPlugins
     */
    public function __construct(PageMapBuilderInterface $pageMapBuilder, array $pageMapPlugins = [])
    {
        $this->pageMapBuilder = $pageMapBuilder;
        $this->pageMapPlugins = $this->mapPluginClassesByName($pageMapPlugins);
        $this->underscoreToDashFilter = new UnderscoreToDash();
        $this->pageIndexMap = new PageIndexMap();
    }

    /**
     * @param \Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\PageMapPluginInterface[] $namedPageMapPlugins
     *
     * @return array
     */
    protected function mapPluginClassesByName(array $namedPageMapPlugins): array
    {
        $pageMapPlugins = [];
        foreach ($namedPageMapPlugins as $namedPageMapPlugin) {
            $pageMapPlugins[$namedPageMapPlugin->getName()] = $namedPageMapPlugin;
        }

        return $pageMapPlugins;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return array
     */
    public function mapRawDataToSearchData(array $data, DataMappingContextTransfer $dataMappingContextTransfer): array
    {
        $result = [];
        $localeTransfer = $dataMappingContextTransfer->requireLocale()->getLocale();
        $mapperName = $dataMappingContextTransfer->requireResourceName()->getResourceName();
        $pageMapPlugin = $this->getPageMapPluginByName($mapperName);
        $pageMapTransfer = $pageMapPlugin->buildPageMap($this->pageMapBuilder, $data, $localeTransfer);

        foreach ($pageMapTransfer->modifiedToArray() as $key => $value) {
            $normalizedKey = $this->normalizeKey($key);

            $result = $this->mapValue($pageMapTransfer, $normalizedKey, $value, $result);
        }

        return $result;
    }

    /**
     * @param string $key
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function normalizeKey($key): string
    {
        if (in_array($key, $this->pageIndexMap->getProperties())) {
            return $key;
        }

        $normalizedKey = $this->underscoreToDashFilter->filter($key);

        if (in_array($normalizedKey, $this->pageIndexMap->getProperties())) {
            return $normalizedKey;
        }

        throw new Exception(sprintf('Unable to map %s property in %s', $key, get_class($this->pageIndexMap)));
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $normalizedKey
     * @param mixed $value
     * @param array $result
     *
     * @return array
     */
    protected function mapValue(PageMapTransfer $pageMapTransfer, $normalizedKey, $value, array $result): array
    {
        switch ($normalizedKey) {
            case PageIndexMap::SEARCH_RESULT_DATA:
                $result = $this->transformSearchResultData($result, $pageMapTransfer->getSearchResultData());
                break;
            case PageIndexMap::STRING_FACET:
                $result = $this->transformStringFacet($result, $pageMapTransfer->getStringFacet());
                break;
            case PageIndexMap::INTEGER_FACET:
                $result = $this->transformIntegerFacet($result, $pageMapTransfer->getIntegerFacet());
                break;
            case PageIndexMap::STRING_SORT:
                $result = $this->transformStringSort($result, $pageMapTransfer->getStringSort());
                break;
            case PageIndexMap::INTEGER_SORT:
                $result = $this->transformIntegerSort($result, $pageMapTransfer->getIntegerSort());
                break;
            case PageIndexMap::CATEGORY:
                $result = $this->transformCategory($result, $pageMapTransfer->getCategory());
                break;
            default:
                $result = $this->transformOther($result, $normalizedKey, $value);
        }

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\SearchResultDataMapTransfer[]|\ArrayObject $searchResultData
     *
     * @return array
     */
    protected function transformSearchResultData(array $result, $searchResultData): array
    {
        foreach ($searchResultData as $searchResultDataMapTransfer) {
            $searchResultDataMapTransfer
                ->requireName()
                ->requireValue();

            $result[PageIndexMap::SEARCH_RESULT_DATA][$searchResultDataMapTransfer->getName()] = $searchResultDataMapTransfer->getValue();
        }

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\StringFacetMapTransfer[]|\ArrayObject $stringFacetMap
     *
     * @return array
     */
    protected function transformStringFacet(array $result, $stringFacetMap): array
    {
        foreach ($stringFacetMap as $stringFacetMapTransfer) {
            $stringFacetMapTransfer
                ->requireName()
                ->requireValue();

            $result[PageIndexMap::STRING_FACET][] = [
                self::FACET_NAME => $stringFacetMapTransfer->getName(),
                self::FACET_VALUE => $stringFacetMapTransfer->getValue(),
            ];
        }

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\IntegerFacetMapTransfer[]|\ArrayObject $integerFacet
     *
     * @return array
     */
    protected function transformIntegerFacet(array $result, $integerFacet): array
    {
        foreach ($integerFacet as $integerFacetMapTransfer) {
            $integerFacetMapTransfer
                ->requireName()
                ->requireValue();

            $result[PageIndexMap::INTEGER_FACET][] = [
                self::FACET_NAME => $integerFacetMapTransfer->getName(),
                self::FACET_VALUE => $integerFacetMapTransfer->getValue(),
            ];
        }

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\StringSortMapTransfer[]|\ArrayObject $stringSortMap
     *
     * @return array
     */
    protected function transformStringSort(array $result, $stringSortMap): array
    {
        foreach ($stringSortMap as $stringSortMapTransfer) {
            $stringSortMapTransfer
                ->requireName()
                ->requireValue();

            $result[PageIndexMap::STRING_SORT][$stringSortMapTransfer->getName()] = $stringSortMapTransfer->getValue();
        }

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\IntegerSortMapTransfer[]|\ArrayObject $integerSortMap
     *
     * @return array
     */
    protected function transformIntegerSort(array $result, $integerSortMap): array
    {
        foreach ($integerSortMap as $stringSortMapTransfer) {
            $stringSortMapTransfer
                ->requireName()
                ->requireValue();

            $result[PageIndexMap::INTEGER_SORT][$stringSortMapTransfer->getName()] = $stringSortMapTransfer->getValue();
        }

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\CategoryMapTransfer $categoryMap
     *
     * @return array
     */
    protected function transformCategory(array $result, CategoryMapTransfer $categoryMap): array
    {
        $categoryMap
            ->requireAllParents()
            ->requireDirectParents();

        $result[PageIndexMap::CATEGORY] = [
            self::ALL_PARENTS => $categoryMap->getAllParents(),
            self::DIRECT_PARENTS => $categoryMap->getDirectParents(),
        ];

        return $result;
    }

    /**
     * @param array $result
     * @param string $key
     * @param mixed $value
     *
     * @return array
     */
    protected function transformOther(array $result, $key, $value): array
    {
        $result[$key] = $value;

        return $result;
    }

    /**
     * @param string $pluginName
     *
     * @throws \Spryker\Zed\SearchElasticsearch\Business\Exception\PageMapPluginNotFoundException
     *
     * @return \Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\PageMapPluginInterface
     */
    protected function getPageMapPluginByName(string $pluginName): PageMapPluginInterface
    {
        foreach ($this->pageMapPlugins as $pageMapPlugin) {
            if ($pageMapPlugin->getName() == $pluginName) {
                return $pageMapPlugin;
            }
        }

        throw new PageMapPluginNotFoundException(sprintf('PageMap plugin with this name: `%s` cannot be found', $pluginName));
    }
}
