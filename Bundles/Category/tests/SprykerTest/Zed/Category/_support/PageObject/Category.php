<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\PageObject;

class Category
{
    /**
     * @var string
     */
    public const FORM_FIELD_CATEGORY_KEY = 'category[category_key]';

    /**
     * @var string
     */
    public const FORM_FIELD_CATEGORY_PARENT = 'category[parent_category_node]';

    /**
     * @var string
     */
    public const FORM_FIELD_CATEGORY_TEMPLATE = 'category[fk_category_template]';

    /**
     * @var string
     */
    public const FORM_FIELD_CATEGORY_IS_ACTIVE = 'category[is_active]';

    /**
     * @var string
     */
    public const FORM_FIELD_CATEGORY_IS_IN_MENU = 'category[is_in_menu]';

    /**
     * @var string
     */
    public const FORM_FIELD_CATEGORY_IS_MAIN = 'category[is_main]';

    /**
     * @var string
     */
    public const FORM_FIELD_CATEGORY_IS_CLICKABLE = 'category[is_clickable]';

    /**
     * @var string
     */
    public const FORM_FIELD_CATEGORY_IS_SEARCHABLE = 'category[is_searchable]';

    /**
     * @var string
     */
    public const FORM_FIELD_CATEGORY_NAME_PATTERN = 'category[localized_attributes][%d][name]';

    /**
     * @var string
     */
    public const FORM_FIELD_CATEGORY_TITLE_PATTERN = 'category[localized_attributes][%d][meta_title]';

    /**
     * @var string
     */
    public const FORM_FIELD_CATEGORY_DESCRIPTION_PATTERN = 'category[localized_attributes][%d][meta_description]';

    /**
     * @var string
     */
    public const FORM_FIELD_CATEGORY_KEYWORDS_PATTERN = 'category[localized_attributes][%d][meta_keywords]';

    /**
     * @var string
     */
    public const CATEGORY_A = 'category-a';

    /**
     * @var string
     */
    public const CATEGORY_B = 'category-b';

    /**
     * @param string $categoryKey
     *
     * @return array
     */
    public static function getCategorySelectorsWithValues(string $categoryKey): array
    {
        return [
            static::FORM_FIELD_CATEGORY_KEY => $categoryKey,
            static::FORM_FIELD_CATEGORY_PARENT => 1,
            static::FORM_FIELD_CATEGORY_TEMPLATE => 1,
            'attributes' => [
                'en_US' => static::getAttributesSelector($categoryKey, 'en_US', 0),
                'de_DE' => static::getAttributesSelector($categoryKey, 'de_DE', 1),
            ],
        ];
    }

    /**
     * @param string $name
     * @param string $localeName
     * @param int $position
     *
     * @return array
     */
    public static function getAttributesSelector(string $name, string $localeName, int $position): array
    {
        return [
            static::getFieldSelectorCategoryName($position) => $name . ' ' . $localeName,
            static::getFieldSelectorCategoryTitle($position) => $name . ' ' . $localeName . ' Title',
            static::getFieldSelectorCategoryDescription($position) => $name . ' ' . $localeName . ' Description',
            static::getFieldSelectorCategoryKeywords($position) => $name . ' ' . $localeName . ' Keywords',
        ];
    }

    /**
     * @param int $position
     *
     * @return string
     */
    public static function getFieldSelectorCategoryName(int $position): string
    {
        return sprintf(static::FORM_FIELD_CATEGORY_NAME_PATTERN, $position);
    }

    /**
     * @param int $position
     *
     * @return string
     */
    public static function getFieldSelectorCategoryTitle(int $position): string
    {
        return sprintf(static::FORM_FIELD_CATEGORY_TITLE_PATTERN, $position);
    }

    /**
     * @param int $position
     *
     * @return string
     */
    public static function getFieldSelectorCategoryDescription(int $position): string
    {
        return sprintf(static::FORM_FIELD_CATEGORY_DESCRIPTION_PATTERN, $position);
    }

    /**
     * @param int $position
     *
     * @return string
     */
    public static function getFieldSelectorCategoryKeywords(int $position): string
    {
        return sprintf(static::FORM_FIELD_CATEGORY_KEYWORDS_PATTERN, $position);
    }
}
