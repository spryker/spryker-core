<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model;

class FacetConfig
{

    const KEY_FACET_FIELD_NAME = 'facet_field_name';
    const KEY_SORT_FIELD_NAME = 'sort_field_name';
    const KEY_MULTI_VALUED = 'multi_valued';
    const KEY_PARAM = 'param';
    const KEY_FACET_ACTIVE = 'facet_active';
    const KEY_SORT_ACTIVE = 'sort_active';
    const KEY_TYPE = 'type';
    const KEY_IN_URL = 'in_url';
    const KEY_SHORT_PARAM = 'short_param';
    const KEY_URL_POSITION = 'key_url_position';
    const KEY_RANGE_DIVIDER = 'range_divider';
    const KEY_VALUE_CALLBACK_BEFORE = 'value_callback_before';
    const KEY_VALUE_CALLBACK_AFTER = 'value_callback_after';
    const KEY_RETURN_ZERO_VALUES = 'key_return_zero_values';

    const TYPE_ENUMERATION = 'enumeration';
    const TYPE_CATEGORY = 'category';
    const TYPE_SLIDER = 'slider';
    const TYPE_BOOL = 'bool';

    const FIELD_STRING_FACET = 'string-facet';
    const FIELD_INTEGER_FACET = 'integer-facet';
    const FIELD_FLOAT_FACET = 'float-facet';
    const FIELD_INTEGER_SORT = 'integer-sort';
    const FIELD_FLOAT_SORT = 'float-sort';
    const FIELD_STRING_SORT = 'string-sort';
    const FIELD_SEARCH_RESULT_DATA = 'search-result-data';

    /**
     * @var array
     */
    protected static $attributes = [];

    /**
     * @var array
     */
    protected static $sortNamesMapping = [];

    /**
     * @var array
     */
    protected static $stringFacetFields = [];

    /**
     * @var array
     */
    protected static $numericFacetFields = [];

    /**
     * @param string $sortParam
     *
     * @return mixed|null
     */
    public function getSortFieldFromParam($sortParam)
    {
        $attributeConfig = self::getFacetSetupFromParameter($sortParam);
        if ($attributeConfig[self::KEY_SORT_FIELD_NAME]) {
            return $attributeConfig[self::KEY_SORT_FIELD_NAME];
        }

        return;
    }

    /**
     * @param string $internalName
     */
    public function getSortNameFromInternalName($internalName)
    {
        return isset(static::$sortNamesMapping[$internalName]) ? static::$sortNamesMapping[$internalName] : null;
    }

    /**
     * @return array
     */
    public function getAllSortNames()
    {
        return array_values(static::$sortNamesMapping);
    }

    /**
     * @param string $facetName
     *
     * @return string|null
     */
    public function getParameterNameForFacet($facetName)
    {
        return isset(static::$attributes[$facetName]) ? static::$attributes[$facetName] : null;
    }

    /**
     * @param string $paramName
     *
     * @throws \RuntimeException
     *
     * @return string|null
     */
    public function getFacetNameFromParameter($paramName)
    {
        $callback = function ($facet) use ($paramName) {
            return self::filterFacetByParamNameCallback($facet, $paramName);
        };
        $facetForParam = array_filter(static::$attributes, $callback);
        $keys = array_keys($facetForParam);
        if (count($keys) > 1) {
            throw new \RuntimeException('Parameter names for Facets must be unique, Duplicates found for param: ' . $paramName);
        }

        return array_pop($keys);
    }

    /**
     * @param string $shortParamName
     *
     * @throws \RuntimeException
     *
     * @return string|null
     */
    public function getFacetNameFromShortParameter($shortParamName)
    {
        $callback = function ($facet) use ($shortParamName) {
            return self::filterFacetByShortParamNameCallback($facet, $shortParamName);
        };
        $facetForParam = array_filter(static::$attributes, $callback);
        $keys = array_keys($facetForParam);
        if (count($keys) > 1) {
            throw new \RuntimeException('Short Parameter names for Facets must be unique, Duplicates found for short param: ' . $shortParamName);
        }

        return array_pop($keys);
    }

    /**
     * @param string $paramName
     *
     * @return array|mixed
     */
    public function getFacetSetupFromParameter($paramName)
    {
        $callback = function ($facet) use ($paramName) {
            return self::filterFacetByParamNameCallback($facet, $paramName);
        };
        $facetForParam = array_filter(static::$attributes, $callback);

        if (count($facetForParam) > 0) {
            return array_pop($facetForParam);
        } else {
            return [];
        }
    }

    /**
     * @param string $shortParamName
     *
     * @throws \RuntimeException
     *
     * @return string|null
     */
    public function getParameterNameForShortParameter($shortParamName)
    {
        $callback = function ($facet) use ($shortParamName) {
            return self::filterFacetByShortParamNameCallback($facet, $shortParamName);
        };
        $facetForParam = array_filter(static::$attributes, $callback);
        $keys = array_keys($facetForParam);
        if (count($keys) > 1) {
            throw new \RuntimeException('Short Parameter names for Facets must be unique, Duplicates found for short param: ' . $shortParamName);
        }

        $facetSetup = array_pop($facetForParam);

        return $facetSetup[self::KEY_PARAM];
    }

    /**
     * @return array
     */
    public function getActiveFacets()
    {
        return array_filter(static::$attributes, [__CLASS__, 'filterActiveFacetsCallback']);
    }

    /**
     * @return array
     */
    public function getActiveInUrlFacets()
    {
        return array_filter(static::$attributes, [__CLASS__, 'filterActiveInUrlFacetsCallback']);
    }

    /**
     * @return array
     */
    public function getActiveSortAttributes()
    {
        return array_filter(static::$attributes, [__CLASS__, 'filterActiveSortAttributesCallback']);
    }

    /**
     * @param bool $onlyActive
     *
     * @return array
     */
    public function getAllParamNamesForFacets($onlyActive = false)
    {
        $paramNames = [];
        foreach (static::$attributes as $facet) {
            if ($onlyActive) {
                if ($facet[self::KEY_FACET_ACTIVE] === true) {
                    $paramNames[] = $facet[self::KEY_PARAM];
                }
            } else {
                $paramNames[] = $facet[self::KEY_PARAM];
            }
        }

        return $paramNames;
    }

    /**
     * @return string
     */
    public function getStringFacetField()
    {
        return self::FIELD_STRING_FACET;
    }

    /**
     * @return string
     */
    public function getIntegerFacetField()
    {
        return self::FIELD_INTEGER_FACET;
    }

    /**
     * @return string
     */
    public function getFloatFacetField()
    {
        return self::FIELD_FLOAT_FACET;
    }

    /**
     * @return array
     */
    public function getFacetFields()
    {
        return array_merge(static::$stringFacetFields, static::$numericFacetFields);
    }

    /**
     * @return array
     */
    public function getNumericFacetFields()
    {
        return static::$numericFacetFields;
    }

    /**
     * @param array $attribute
     *
     * @return bool
     */
    protected static function filterActiveFacetsCallback(array $attribute)
    {
        return $attribute[self::KEY_FACET_ACTIVE];
    }

    /**
     * @param array $attribute
     *
     * @return bool
     */
    protected static function filterActiveSortAttributesCallback(array $attribute)
    {
        return $attribute[self::KEY_SORT_ACTIVE];
    }

    /**
     * @param array $facet
     *
     * @return bool
     */
    protected static function filterActiveInUrlFacetsCallback(array $facet)
    {
        return $facet[self::KEY_FACET_ACTIVE] && isset($facet[self::KEY_IN_URL]) && $facet[self::KEY_IN_URL];
    }

    /**
     * @param array $facet
     * @param string $paramName
     *
     * @return bool
     */
    protected static function filterFacetByParamNameCallback($facet, $paramName)
    {
        return $facet[self::KEY_PARAM] === $paramName;
    }

    /**
     * @param array $facet
     * @param string $shortParamName
     *
     * @return bool
     */
    protected static function filterFacetByShortParamNameCallback($facet, $shortParamName)
    {
        return isset($facet[self::KEY_SHORT_PARAM]) && $facet[self::KEY_SHORT_PARAM] === $shortParamName;
    }

}
