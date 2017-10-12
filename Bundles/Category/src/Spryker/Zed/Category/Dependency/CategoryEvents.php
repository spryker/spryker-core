<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Dependency;

interface CategoryEvents
{
    const CATEGORY_BEFORE_CREATE = 'Category.before.create';
    const CATEGORY_BEFORE_UPDATE = 'Category.before.update';
    const CATEGORY_BEFORE_DELETE = 'Category.before.delete';

    const CATEGORY_AFTER_CREATE = 'Category.after.create';
    const CATEGORY_AFTER_UPDATE = 'Category.after.update';
    const CATEGORY_AFTER_DELETE = 'Category.after.delete';
}
