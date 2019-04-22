<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\CategoryMapTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\Search\Business\Exception\InvalidPropertyNameException;
use Spryker\Zed\Search\Business\Exception\PluginNotFoundException;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;
use Zend\Filter\Word\UnderscoreToDash;

class PageDataMapper implements PageDataMapperInterface
{
    public const FACET_NAME = 'facet-name';
    public const FACET_VALUE = 'facet-value';
    public const ALL_PARENTS = 'all-parents';
    public const DIRECT_PARENTS = 'direct-parents';

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface
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
     * @var \Spryker\Zed\Search\Dependency\Plugin\NamedPageMapInterface[]
     */
    protected $pageMapInterfaces = [];

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $namedPageMapPlugins
     */
    public function __construct(PageMapBuilderInterface $pageMapBuilder, array $namedPageMapPlugins = [])
    {
        $this->pageMapBuilder = $pageMapBuilder;
        $this->pageMapInterfaces = $this->mapPluginClassesByName($namedPageMapPlugins);
        $this->underscoreToDashFilter = new UnderscoreToDash();
        $this->pageIndexMap = new PageIndexMap();
    }

    /**
     * @param \Spryker\Zed\Search\Dependency\Plugin\NamedPageMapInterface[] $namedPageMapPlugins
     *
     * @return array
     */
    protected function mapPluginClassesByName(array $namedPageMapPlugins)
    {
        $pageMaps = [];
        foreach ($namedPageMapPlugins as $namedPageMapPlugin) {
            $pageMaps[$namedPageMapPlugin->getName()] = $namedPageMapPlugin;
        }

        return $pageMaps;
    }

    /**
     * @deprecated Use transferDataByMapperName() instead.
     *
     * @param \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface $pageMap
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapData(PageMapInterface $pageMap, array $data, LocaleTransfer $localeTransfer)
    {
        $result = [];

        $pageMapTransfer = $pageMap->buildPageMap($this->pageMapBuilder, $data, $localeTransfer);

        foreach ($pageMapTransfer->modifiedToArray() as $key => $value) {
            $normalizedKey = $this->normalizeKey($key);

            $result = $this->mapValue($pageMapTransfer, $normalizedKey, $value, $result);
        }

        return $result;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $mapperName
     *
     * @throws \Spryker\Zed\Search\Business\Exception\PluginNotFoundException
     *
     * @return array
     */
    public function transferDataByMapperName(array $data, LocaleTransfer $localeTransfer, $mapperName)
    {
        $result = [];

        if (!isset($this->pageMapInterfaces[$mapperName])) {
            throw new PluginNotFoundException(sprintf('PageMap plugin with this name: `%s` cannot be found', $mapperName));
        }

        $pageMap = $this->pageMapInterfaces[$mapperName];
        $pageMapTransfer = $pageMap->buildPageMap($this->pageMapBuilder, $data, $localeTransfer);

        foreach ($pageMapTransfer->modifiedToArray() as $key => $value) {
            $normalizedKey = $this->normalizeKey($key);

            $result = $this->mapValue($pageMapTransfer, $normalizedKey, $value, $result);
        }

        return $result;
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Zed\Search\Business\Exception\InvalidPropertyNameException
     *
     * @return string
     */
    protected function normalizeKey($key)
    {
        if (in_array($key, $this->pageIndexMap->getProperties())) {
            return $key;
        }

        $normalizedKey = $this->underscoreToDashFilter->filter($key);

        if (in_array($normalizedKey, $this->pageIndexMap->getProperties())) {
            return $normalizedKey;
        }

        throw new InvalidPropertyNameException(sprintf('Unable to map %s property in %s', $key, get_class($this->pageIndexMap)));
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $normalizedKey
     * @param mixed $value
     * @param array $result
     *
     * @return array
     */
    protected function mapValue(PageMapTransfer $pageMapTransfer, $normalizedKey, $value, array $result)
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
    protected function transformSearchResultData(array $result, $searchResultData)
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
    protected function transformStringFacet(array $result, $stringFacetMap)
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
    protected function transformIntegerFacet(array $result, $integerFacet)
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
    protected function transformStringSort(array $result, $stringSortMap)
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
    protected function transformIntegerSort(array $result, $integerSortMap)
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
    protected function transformCategory(array $result, CategoryMapTransfer $categoryMap)
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
    protected function transformOther(array $result, $key, $value)
    {
        $result[$key] = $value;

        return $result;
    }
}
