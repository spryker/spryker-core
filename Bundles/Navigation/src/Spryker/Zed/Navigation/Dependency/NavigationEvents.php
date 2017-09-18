<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Dependency;

use Orm\Zed\Navigation\Persistence\Map\SpyNavigationNodeLocalizedAttributesTableMap;
use Orm\Zed\Navigation\Persistence\Map\SpyNavigationNodeTableMap;
use Orm\Zed\Navigation\Persistence\Map\SpyNavigationTableMap;

interface NavigationEvents
{

    const NAVIGATION_MENU_PUBLISH = 'Navigation.menu.publish';
    const NAVIGATION_MENU_UNPUBLISH = 'Navigation.menu.unpublish';

    const ENTITY_SPY_NAVIGATION_CREATE = 'Entity.' . SpyNavigationTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_NAVIGATION_UPDATE = 'Entity.' . SpyNavigationTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_NAVIGATION_DELETE = 'Entity.' . SpyNavigationTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_NAVIGATION_NODE_CREATE = 'Entity.' . SpyNavigationNodeTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_NAVIGATION_NODE_UPDATE = 'Entity.' . SpyNavigationNodeTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_NAVIGATION_NODE_DELETE = 'Entity.' . SpyNavigationNodeTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_CREATE = 'Entity.' . SpyNavigationNodeLocalizedAttributesTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_UPDATE = 'Entity.' . SpyNavigationNodeLocalizedAttributesTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_DELETE = 'Entity.' . SpyNavigationNodeLocalizedAttributesTableMap::TABLE_NAME . '.delete';

}
