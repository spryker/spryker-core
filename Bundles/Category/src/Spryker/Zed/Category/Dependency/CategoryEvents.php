<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Dependency;

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

    const ENTITY_SPY_CATEGORY_CREATE = 'Entity.spy_category.create';
    const ENTITY_SPY_CATEGORY_UPDATE = 'Entity.spy_category.update';
    const ENTITY_SPY_CATEGORY_DELETE = 'Entity.spy_category.delete';

    const ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE = 'Entity.spy_category_attribute.create';
    const ENTITY_SPY_CATEGORY_ATTRIBUTE_UPDATE = 'Entity.spy_category_attribute.update';
    const ENTITY_SPY_CATEGORY_ATTRIBUTE_DELETE = 'Entity.spy_category_attribute.delete';

    const ENTITY_SPY_CATEGORY_CLOSURE_TABLE_CREATE = 'Entity.spy_category_closure_table.create';
    const ENTITY_SPY_CATEGORY_CLOSURE_TABLE_UPDATE = 'Entity.spy_category_closure_table.update';
    const ENTITY_SPY_CATEGORY_CLOSURE_TABLE_DELETE = 'Entity.spy_category_closure_table.delete';

    const ENTITY_SPY_CATEGORY_NODE_CREATE = 'Entity.spy_category_node.create';
    const ENTITY_SPY_CATEGORY_NODE_UPDATE = 'Entity.spy_category_node.update';
    const ENTITY_SPY_CATEGORY_NODE_DELETE = 'Entity.spy_category_node.delete';

    const ENTITY_SPY_CATEGORY_TEMPLATE_CREATE = 'Entity.spy_category_template.create';
    const ENTITY_SPY_CATEGORY_TEMPLATE_UPDATE = 'Entity.spy_category_template.update';
    const ENTITY_SPY_CATEGORY_TEMPLATE_DELETE = 'Entity.spy_category_template.delete';

}
