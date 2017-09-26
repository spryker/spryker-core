<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Dependency;

interface NavigationEvents
{

    const NAVIGATION_MENU_PUBLISH = 'Navigation.menu.publish';
    const NAVIGATION_MENU_UNPUBLISH = 'Navigation.menu.unpublish';

    const ENTITY_SPY_NAVIGATION_CREATE = 'Entity.spy_navigation.create';
    const ENTITY_SPY_NAVIGATION_UPDATE = 'Entity.spy_navigation.update';
    const ENTITY_SPY_NAVIGATION_DELETE = 'Entity.spy_navigation.delete';

    const ENTITY_SPY_NAVIGATION_NODE_CREATE = 'Entity.spy_navigation_node.create';
    const ENTITY_SPY_NAVIGATION_NODE_UPDATE = 'Entity.spy_navigation_node.update';
    const ENTITY_SPY_NAVIGATION_NODE_DELETE = 'Entity.spy_navigation_node.delete';

    const ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_CREATE = 'Entity.spy_navigation_node_localized_attributes.create';
    const ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_UPDATE = 'Entity.spy_navigation_node_localized_attributes.update';
    const ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_DELETE = 'Entity.spy_navigation_node_localized_attributes.delete';

}
