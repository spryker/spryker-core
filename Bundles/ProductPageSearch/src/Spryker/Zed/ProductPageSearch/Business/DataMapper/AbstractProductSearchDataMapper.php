<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\DataMapper;

use Exception;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\CategoryMapTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Laminas\Filter\Word\UnderscoreToDash;

abstract class AbstractProductSearchDataMapper
{
    protected const FACET_NAME = 'facet-name';
    protected const FACET_VALUE = 'facet-value';
    protected const ALL_PARENTS = 'all-parents';
    protected const DIRECT_PARENTS = 'direct-parents';

    /**
     * @var \Laminas\Filter\Word\UnderscoreToDash
     */
    protected $underscoreToDashFilter;

    public function __construct()
    {
        $this->underscoreToDashFilter = new UnderscoreToDash();
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    abstract public function mapProductDataToSearchData(array $data, LocaleTransfer $localeTransfer): array;

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function buildProductPageSearchData(array $data, LocaleTransfer $localeTransfer): array
    {
        $result = [];
        $pageMapTransfer = $this->buildPageMap($data, $localeTransfer);

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
        $pageIndexMapProperties = $this->getPageIndexMapProperties();

        if (in_array($key, $pageIndexMapProperties)) {
            return $key;
        }

        $normalizedKey = $this->underscoreToDashFilter->filter($key);

        if (in_array($normalizedKey, $pageIndexMapProperties)) {
            return $normalizedKey;
        }

        throw new Exception(sprintf('Unable to map %s property in %s', $key, PageIndexMap::class));
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
                static::FACET_NAME => $stringFacetMapTransfer->getName(),
                static::FACET_VALUE => $stringFacetMapTransfer->getValue(),
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
                static::FACET_NAME => $integerFacetMapTransfer->getName(),
                static::FACET_VALUE => $integerFacetMapTransfer->getValue(),
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
            static::ALL_PARENTS => $categoryMap->getAllParents(),
            static::DIRECT_PARENTS => $categoryMap->getDirectParents(),
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
     * @return string[]
     */
    protected function getPageIndexMapProperties(): array
    {
        return (new PageIndexMap())->getProperties();
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    abstract protected function buildPageMap(array $data, LocaleTransfer $localeTransfer): PageMapTransfer;
}
