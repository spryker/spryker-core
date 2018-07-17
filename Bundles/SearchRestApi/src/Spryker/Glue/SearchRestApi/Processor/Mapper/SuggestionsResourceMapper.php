<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestSearchSuggestionsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\SearchRestApi\SearchRestApiConfig;

class SuggestionsResourceMapper implements SuggestionsResourceMapperInterface
{
    protected const SEARCH_RESPONSE_COMPLETION_KEY = 'completion';
    protected const SEARCH_RESPONSE_SUGGESTION_BY_TYPE_KEY = 'suggestionByType';
    protected const SEARCH_RESPONSE_PRODUCT_ABSTRACT_KEY = 'product_abstract';
    protected const SEARCH_RESPONSE_CATEGORY_KEY = 'category';
    protected const SEARCH_RESPONSE_CMS_PAGE_KEY = 'cms_page';

    protected const SEARCH_RESPONSE_ABSTRACT_SKU_KEY = 'abstract_sku';
    protected const SEARCH_RESPONSE_PRICE_KEY = 'price';
    protected const SEARCH_RESPONSE_ABSTRACT_NAME_KEY = 'abstract_name';
    protected const SEARCH_RESPONSE_NAME_KEY = 'name';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @return array
     */
    public function getSearchResponseDefaultStructure(): array
    {
        return [
            static::SEARCH_RESPONSE_COMPLETION_KEY => [],
            static::SEARCH_RESPONSE_SUGGESTION_BY_TYPE_KEY => [
                static::SEARCH_RESPONSE_PRODUCT_ABSTRACT_KEY => [],
                static::SEARCH_RESPONSE_CATEGORY_KEY => [],
                static::SEARCH_RESPONSE_CMS_PAGE_KEY => [],
            ],
        ];
    }

    /**
     * @param array $restSearchResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapSuggestionsResponseAttributesTransferToRestResponse(array $restSearchResponse): RestResourceInterface
    {
        $restSuggestionsAttributesTransfer = new RestSearchSuggestionsAttributesTransfer();
        $restSuggestionsAttributesTransfer->fromArray($restSearchResponse, true);

        $restSuggestionsAttributesTransfer = $this->mapCustomFields($restSuggestionsAttributesTransfer, $restSearchResponse);

        return $this->restResourceBuilder->createRestResource(
            SearchRestApiConfig::RESOURCE_SUGGESTIONS,
            null,
            $restSuggestionsAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestSearchSuggestionsAttributesTransfer $restSuggestionsAttributesTransfer
     * @param array $restSearchResponse
     *
     * @return \Generated\Shared\Transfer\RestSearchSuggestionsAttributesTransfer
     */
    protected function mapCustomFields(RestSearchSuggestionsAttributesTransfer $restSuggestionsAttributesTransfer, array $restSearchResponse): RestSearchSuggestionsAttributesTransfer
    {
        $restSuggestionsAttributesTransfer = $this->mapProductSuggestions($restSuggestionsAttributesTransfer, $restSearchResponse);
        $restSuggestionsAttributesTransfer = $this->mapCategorySuggestions($restSuggestionsAttributesTransfer, $restSearchResponse);
        $restSuggestionsAttributesTransfer = $this->mapCmsPageSuggestions($restSuggestionsAttributesTransfer, $restSearchResponse);

        return $restSuggestionsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestSearchSuggestionsAttributesTransfer $restSearchSuggestionsAttributesTransfer
     * @param array $restSearchResponse
     *
     * @return \Generated\Shared\Transfer\RestSearchSuggestionsAttributesTransfer
     */
    protected function mapProductSuggestions(RestSearchSuggestionsAttributesTransfer $restSearchSuggestionsAttributesTransfer, array $restSearchResponse): RestSearchSuggestionsAttributesTransfer
    {
        $suggestionName = static::SEARCH_RESPONSE_PRODUCT_ABSTRACT_KEY;
        $suggestionKeysRequired = [
            static::SEARCH_RESPONSE_ABSTRACT_SKU_KEY,
            static::SEARCH_RESPONSE_PRICE_KEY,
            static::SEARCH_RESPONSE_ABSTRACT_NAME_KEY,
        ];
        $productsSuggestions = $this->mapSuggestions($restSearchResponse, $suggestionName, $suggestionKeysRequired);
        $restSearchSuggestionsAttributesTransfer->setProducts($productsSuggestions);

        return $restSearchSuggestionsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestSearchSuggestionsAttributesTransfer $restSearchSuggestionsAttributesTransfer
     * @param array $restSearchResponse
     *
     * @return \Generated\Shared\Transfer\RestSearchSuggestionsAttributesTransfer
     */
    protected function mapCategorySuggestions(RestSearchSuggestionsAttributesTransfer $restSearchSuggestionsAttributesTransfer, array $restSearchResponse): RestSearchSuggestionsAttributesTransfer
    {
        $suggestionName = static::SEARCH_RESPONSE_CATEGORY_KEY;
        $suggestionKeysRequired = [static::SEARCH_RESPONSE_NAME_KEY];
        $categoriesSuggestions = $this->mapSuggestions($restSearchResponse, $suggestionName, $suggestionKeysRequired);
        $restSearchSuggestionsAttributesTransfer->setCategories($categoriesSuggestions);

        return $restSearchSuggestionsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestSearchSuggestionsAttributesTransfer $restSearchSuggestionsAttributesTransfer
     * @param array $restSearchResponse
     *
     * @return \Generated\Shared\Transfer\RestSearchSuggestionsAttributesTransfer
     */
    protected function mapCmsPageSuggestions(RestSearchSuggestionsAttributesTransfer $restSearchSuggestionsAttributesTransfer, array $restSearchResponse): RestSearchSuggestionsAttributesTransfer
    {
        $suggestionName = static::SEARCH_RESPONSE_CMS_PAGE_KEY;
        $suggestionKeysRequired = [static::SEARCH_RESPONSE_NAME_KEY];
        $cmsPagesSuggestions = $this->mapSuggestions($restSearchResponse, $suggestionName, $suggestionKeysRequired);
        $restSearchSuggestionsAttributesTransfer->setCmsPages($cmsPagesSuggestions);

        return $restSearchSuggestionsAttributesTransfer;
    }

    /**
     * @param array $restSearchResponse
     * @param string $suggestionName
     * @param array $suggestionKeysRequired
     *
     * @return array
     */
    protected function mapSuggestions(array $restSearchResponse, string $suggestionName, array $suggestionKeysRequired): array
    {
        $result = [];

        if (!$this->checkSuggestionByTypeValues($restSearchResponse, $suggestionName)) {
            return $result;
        }

        foreach ($restSearchResponse[static::SEARCH_RESPONSE_SUGGESTION_BY_TYPE_KEY][$suggestionName] as $cmsPage) {
            if ($this->checkSuggestionsValuesExists($cmsPage, $suggestionKeysRequired)) {
                $result[] = array_intersect_key($cmsPage, array_flip($suggestionKeysRequired));
            }
        }

        return $result;
    }

    /**
     * @param array $restSearchResponse
     * @param string $checkKey
     *
     * @return bool
     */
    protected function checkSuggestionByTypeValues(array $restSearchResponse, string $checkKey): bool
    {
        return isset($restSearchResponse[static::SEARCH_RESPONSE_SUGGESTION_BY_TYPE_KEY][$checkKey])
            && is_array($restSearchResponse[static::SEARCH_RESPONSE_SUGGESTION_BY_TYPE_KEY][$checkKey]);
    }

    /**
     * @param array $suggestions
     * @param array $keys
     *
     * @return bool
     */
    protected function checkSuggestionsValuesExists(array $suggestions, array $keys): bool
    {
        return !array_diff_key(array_flip($keys), $suggestions);
    }
}
