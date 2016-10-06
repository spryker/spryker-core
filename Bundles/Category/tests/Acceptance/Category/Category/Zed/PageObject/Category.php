<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Category\Category\Zed\PageObject;

class Category
{

    const FORM_FIELD_CATEGORY_KEY = 'category[category_key]';
    const FORM_FIELD_CATEGORY_PARENT = 'category[parent]';

    const FORM_FIELD_CATEGORY_NAME_PATTERN = 'category[localized_attributes][%d][name]';
    const FORM_FIELD_CATEGORY_TITLE_PATTERN = 'category[localized_attributes][%d][meta_title]';
    const FORM_FIELD_CATEGORY_DESCRIPTION_PATTERN = 'category[localized_attributes][%d][meta_description]';
    const FORM_FIELD_CATEGORY_KEYWORDS_PATTERN = 'category[localized_attributes][%d][meta_keywords]';

    const CATEGORY_A = 'category-a';
    const CATEGORY_B = 'category-b';

    /**
     * @param $categoryName
     *
     * @return array
     */
    public static function getCategorySelectorsWithValues($categoryName)
    {
        return [
            self::FORM_FIELD_CATEGORY_KEY => $categoryName,
            self::FORM_FIELD_CATEGORY_PARENT => 1,
            'attributes' => [
                'en_US' => self::getAttributesSelector($categoryName, 'en_US', 0),
                'de_DE' => self::getAttributesSelector($categoryName, 'de_DE', 1),
            ]
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
     * @param $position
     *
     * @return string
     */
    public static function getFieldSelectorCategoryName($position)
    {
        return sprintf(self::FORM_FIELD_CATEGORY_NAME_PATTERN, $position);
    }

    /**
     * @param $position
     *
     * @return string
     */
    public static function getFieldSelectorCategoryTitle($position)
    {
        return sprintf(self::FORM_FIELD_CATEGORY_TITLE_PATTERN, $position);
    }

    /**
     * @param $position
     *
     * @return string
     */
    public static function getFieldSelectorCategoryDescription($position)
    {
        return sprintf(self::FORM_FIELD_CATEGORY_DESCRIPTION_PATTERN, $position);
    }

    /**
     * @param $position
     *
     * @return string
     */
    public static function getFieldSelectorCategoryKeywords($position)
    {
        return sprintf(self::FORM_FIELD_CATEGORY_KEYWORDS_PATTERN, $position);
    }

}
