<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Category\Category\Zed\PageObject;

class CategoryCreatePage
{

    const URL = '/category/create';

    const FORM_FIELD_CATEGORY_KEY = 'category[category_key]';
    const FORM_FIELD_CATEGORY_PARENT = 'category[parent]';

    const FORM_FIELD_CATEGORY_NAME = 'category[localized_attributes][0][name]';
    const FORM_FIELD_CATEGORY_TITLE = 'category[localized_attributes][0][meta_title]';
    const FORM_FIELD_CATEGORY_DESCRIPTION = 'category[localized_attributes][0][meta_description]';
    const FORM_FIELD_CATEGORY_KEYWORDS = 'category[localized_attributes][0][meta_keywords]';

    const FORM_SUBMIT_BUTTON = 'Create';

    const CATEGORY_A = 'category-a';
    const CATEGORY_B = 'category-b';

    const CATEGORIES = [
        self::CATEGORY_A => [
            self::FORM_FIELD_CATEGORY_KEY => self::CATEGORY_A,
            self::FORM_FIELD_CATEGORY_PARENT => 1,
            'attributes' => [
                [
                    'locale' => 'de_DE',
                    self::FORM_FIELD_CATEGORY_NAME => self::CATEGORY_A,
                    self::FORM_FIELD_CATEGORY_TITLE => self::CATEGORY_A,
                    self::FORM_FIELD_CATEGORY_DESCRIPTION => self::CATEGORY_A,
                    self::FORM_FIELD_CATEGORY_KEY => self::CATEGORY_A,
                ],
                [
                    'locale' => 'en_US',
                    self::FORM_FIELD_CATEGORY_NAME => self::CATEGORY_A,
                    self::FORM_FIELD_CATEGORY_TITLE => self::CATEGORY_A,
                    self::FORM_FIELD_CATEGORY_DESCRIPTION => self::CATEGORY_A,
                    self::FORM_FIELD_CATEGORY_KEY => self::CATEGORY_A,
                ],
            ]
        ]
    ];

}
