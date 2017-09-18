<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Dependency;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryClosureTableTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTemplateTableMap;

interface CategoryEvents
{

    const CATEGORY_NODE_PUBLISH = 'Category.node.publish';
    const CATEGORY_NODE_UNPUBLISH = 'Category.node.unpublish';

    const CATEGORY_BEFORE_CREATE = 'Category.before.create';
    const CATEGORY_BEFORE_UPDATE = 'Category.before.update';
    const CATEGORY_BEFORE_DELETE = 'Category.before.delete';

    const CATEGORY_AFTER_CREATE = 'Category.after.create';
    const CATEGORY_AFTER_UPDATE = 'Category.after.update';
    const CATEGORY_AFTER_DELETE = 'Category.after.delete';

    const ENTITY_SPY_CATEGORY_CREATE = 'Entity.' . SpyCategoryTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_CATEGORY_UPDATE = 'Entity.' . SpyCategoryTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_CATEGORY_DELETE = 'Entity.' . SpyCategoryTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE = 'Entity.' . SpyCategoryAttributeTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_CATEGORY_ATTRIBUTE_UPDATE = 'Entity.' . SpyCategoryAttributeTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_CATEGORY_ATTRIBUTE_DELETE = 'Entity.' . SpyCategoryAttributeTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_CATEGORY_CLOSURE_TABLE_CREATE = 'Entity.' . SpyCategoryClosureTableTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_CATEGORY_CLOSURE_TABLE_UPDATE = 'Entity.' . SpyCategoryClosureTableTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_CATEGORY_CLOSURE_TABLE_DELETE = 'Entity.' . SpyCategoryClosureTableTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_CATEGORY_NODE_CREATE = 'Entity.' . SpyCategoryNodeTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_CATEGORY_NODE_UPDATE = 'Entity.' . SpyCategoryNodeTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_CATEGORY_NODE_DELETE = 'Entity.' . SpyCategoryNodeTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_CATEGORY_TEMPLATE_CREATE = 'Entity.' . SpyCategoryTemplateTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_CATEGORY_TEMPLATE_UPDATE = 'Entity.' . SpyCategoryTemplateTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_CATEGORY_TEMPLATE_DELETE = 'Entity.' . SpyCategoryTemplateTableMap::TABLE_NAME . '.delete';

}
