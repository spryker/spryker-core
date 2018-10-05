<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\CategoryMapTransfer;
use Generated\Shared\Transfer\IntegerFacetMapTransfer;
use Generated\Shared\Transfer\IntegerSortMapTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\SearchResultDataMapTransfer;
use Generated\Shared\Transfer\StringFacetMapTransfer;
use Generated\Shared\Transfer\StringSortMapTransfer;
use InvalidArgumentException;

class PageMapBuilder implements PageMapBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $fieldName
     * @param string $name
     * @param mixed $value
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function add(PageMapTransfer $pageMapTransfer, $fieldName, $name, $value)
    {
        switch ($fieldName) {
            case PageIndexMap::FULL_TEXT:
                $this->addFullText($pageMapTransfer, $value);
                break;
            case PageIndexMap::FULL_TEXT_BOOSTED:
                $this->addFullTextBoosted($pageMapTransfer, $value);
                break;
            case PageIndexMap::COMPLETION_TERMS:
                $this->addCompletionTerms($pageMapTransfer, $value);
                break;
            case PageIndexMap::SUGGESTION_TERMS:
                $this->addSuggestionTerms($pageMapTransfer, $value);
                break;
            case PageIndexMap::SEARCH_RESULT_DATA:
                $this->addSearchResultData($pageMapTransfer, $name, $value);
                break;
            case PageIndexMap::STRING_FACET:
                $this->addStringFacet($pageMapTransfer, $name, $value);
                break;
            case PageIndexMap::INTEGER_FACET:
                $this->addIntegerFacet($pageMapTransfer, $name, $value);
                break;
            case PageIndexMap::STRING_SORT:
                $this->addStringSort($pageMapTransfer, $name, $value);
                break;
            case PageIndexMap::INTEGER_SORT:
                $this->addIntegerSort($pageMapTransfer, $name, $value);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Adding "%s" field is not supported!', $fieldName));
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function addSearchResultData(PageMapTransfer $pageMapTransfer, $name, $value)
    {
        $searchResultTransfer = (new SearchResultDataMapTransfer())
            ->setName($name)
            ->setValue($value);

        $pageMapTransfer->addSearchResultData($searchResultTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string|array $value
     *
     * @return $this
     */
    public function addFullText(PageMapTransfer $pageMapTransfer, $value)
    {
        $value = $this->ensureArrayValues($value);

        foreach ($value as $oneValue) {
            $pageMapTransfer->addFullText($oneValue);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string|array $value
     *
     * @return $this
     */
    public function addFullTextBoosted(PageMapTransfer $pageMapTransfer, $value)
    {
        $value = $this->ensureArrayValues($value);

        foreach ($value as $oneValue) {
            $pageMapTransfer->addFullTextBoosted($oneValue);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string|array $value
     *
     * @return $this
     */
    public function addSuggestionTerms(PageMapTransfer $pageMapTransfer, $value)
    {
        $value = $this->ensureArrayValues($value);

        foreach ($value as $oneValue) {
            $pageMapTransfer->addSuggestionTerms($oneValue);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string|array $value
     *
     * @return $this
     */
    public function addCompletionTerms(PageMapTransfer $pageMapTransfer, $value)
    {
        $value = $this->ensureArrayValues($value);

        foreach ($value as $oneValue) {
            $pageMapTransfer->addCompletionTerms($oneValue);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param string|array $value
     *
     * @return $this
     */
    public function addStringFacet(PageMapTransfer $pageMapTransfer, $name, $value)
    {
        $value = $this->ensureArrayValues($value);

        $stringFacetMapTransfer = (new StringFacetMapTransfer())
            ->setName($name)
            ->setValue($value);

        $pageMapTransfer->addStringFacet($stringFacetMapTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param int|array $value
     *
     * @return $this
     */
    public function addIntegerFacet(PageMapTransfer $pageMapTransfer, $name, $value)
    {
        $value = $this->ensureArrayValues($value);
        $value = array_map('intval', $value);

        $integerFacetMapTransfer = (new IntegerFacetMapTransfer())
            ->setName($name)
            ->setValue($value);

        $pageMapTransfer->addIntegerFacet($integerFacetMapTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function addStringSort(PageMapTransfer $pageMapTransfer, $name, $value)
    {
        $stringSortMapTransfer = (new StringSortMapTransfer())
            ->setName($name)
            ->setValue($value);

        $pageMapTransfer->addStringSort($stringSortMapTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param int $value
     *
     * @return $this
     */
    public function addIntegerSort(PageMapTransfer $pageMapTransfer, $name, $value)
    {
        $integerSortMapTransfer = (new IntegerSortMapTransfer())
            ->setName($name)
            ->setValue((int)$value);

        $pageMapTransfer->addIntegerSort($integerSortMapTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $allParents
     * @param array $directParents
     *
     * @return $this
     */
    public function addCategory(PageMapTransfer $pageMapTransfer, array $allParents, array $directParents)
    {
        $categoryMapTransfer = new CategoryMapTransfer();
        $categoryMapTransfer
            ->setAllParents($allParents)
            ->setDirectParents($directParents);

        $pageMapTransfer->setCategory($categoryMapTransfer);

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return array
     */
    protected function ensureArrayValues($value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        return array_values(array_filter($value));
    }
}
