<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\PageObject;

class Category
{
    public const FORM_FIELD_CATEGORY_KEY = 'category[category_key]';
    public const FORM_FIELD_CATEGORY_PARENT = 'category[parent_category_node]';
    public const FORM_FIELD_CATEGORY_TEMPLATE = 'category[fk_category_template]';
    public const FORM_FIELD_CATEGORY_IS_ACTIVE = 'category[is_active]';
    public const FORM_FIELD_CATEGORY_IS_IN_MENU = 'category[is_in_menu]';
    public const FORM_FIELD_CATEGORY_IS_MAIN = 'category[is_main]';
    public const FORM_FIELD_CATEGORY_IS_CLICKABLE = 'category[is_clickable]';
    public const FORM_FIELD_CATEGORY_IS_SEARCHABLE = 'category[is_searchable]';

    public const FORM_FIELD_CATEGORY_NAME_PATTERN = 'category[localized_attributes][%d][name]';
    public const FORM_FIELD_CATEGORY_TITLE_PATTERN = 'category[localized_attributes][%d][meta_title]';
    public const FORM_FIELD_CATEGORY_DESCRIPTION_PATTERN = 'category[localized_attributes][%d][meta_description]';
    public const FORM_FIELD_CATEGORY_KEYWORDS_PATTERN = 'category[localized_attributes][%d][meta_keywords]';

    public const CATEGORY_A = 'category-a';
    public const CATEGORY_B = 'category-b';

    /**
     * @param string $categoryKey
     *
     * @return array
     */
    public static function getCategorySelectorsWithValues($categoryKey)
    {
        return [
            self::FORM_FIELD_CATEGORY_KEY => $categoryKey,
            self::FORM_FIELD_CATEGORY_PARENT => 1,
            self::FORM_FIELD_CATEGORY_TEMPLATE => 1,
            'attributes' => [
                'en_US' => self::getAttributesSelector($categoryKey, 'en_US', 0),
                'de_DE' => self::getAttributesSelector($categoryKey, 'de_DE', 1),
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
    public static function getAttributesSelector($name, $localeName, $position)
    {
        return [
            self::getFieldSelectorCategoryName($position) => $name . ' ' . $localeName,
            self::getFieldSelectorCategoryTitle($position) => $name . ' ' . $localeName . ' Title',
            self::getFieldSelectorCategoryDescription($position) => $name . ' ' . $localeName . ' Description',
            self::getFieldSelectorCategoryKeywords($position) => $name . ' ' . $localeName . ' Keywords',
        ];
    }

    /**
     * @param int $position
     *
     * @return string
     */
    public static function getFieldSelectorCategoryName($position)
    {
        return sprintf(self::FORM_FIELD_CATEGORY_NAME_PATTERN, $position);
    }

    /**
     * @param int $position
     *
     * @return string
     */
    public static function getFieldSelectorCategoryTitle($position)
    {
        return sprintf(self::FORM_FIELD_CATEGORY_TITLE_PATTERN, $position);
    }

    /**
     * @param int $position
     *
     * @return string
     */
    public static function getFieldSelectorCategoryDescription($position)
    {
        return sprintf(self::FORM_FIELD_CATEGORY_DESCRIPTION_PATTERN, $position);
    }

    /**
     * @param int $position
     *
     * @return string
     */
    public static function getFieldSelectorCategoryKeywords($position)
    {
        return sprintf(self::FORM_FIELD_CATEGORY_KEYWORDS_PATTERN, $position);
    }
}
