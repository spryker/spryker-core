<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Category\Category\Zed\PageObject;

class CategoryCreatePage
{

    const URL = '/product-category/add';

    const FORM_FIELD_CATEGORY_NAME = 'category[name]';
    const FORM_FIELD_CATEGORY_KEY = 'category[category_key]';
    const FORM_FIELD_CATEGORY_PARENT = 'category[fk_parent_category_node]';
    const FORM_SUBMIT_BUTTON = 'Add';
}
