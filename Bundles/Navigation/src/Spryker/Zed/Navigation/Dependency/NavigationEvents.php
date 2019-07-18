<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Dependency;

interface NavigationEvents
{
    /**
     * Specification
     * - This events will be used for navigation key publishing
     *
     * @api
     */
    public const NAVIGATION_KEY_PUBLISH = 'Navigation.key.publish';

    /**
     * Specification
     * - This events will be used for navigation key un-publishing
     *
     * @api
     */
    public const NAVIGATION_KEY_UNPUBLISH = 'Navigation.key.unpublish';

    /**
     * Specification
     * - This events will be used for spy_navigation entity creation
     *
     * @api
     */
    public const ENTITY_SPY_NAVIGATION_CREATE = 'Entity.spy_navigation.create';

    /**
     * Specification
     * - This events will be used for spy_navigation entity changes
     *
     * @api
     */
    public const ENTITY_SPY_NAVIGATION_UPDATE = 'Entity.spy_navigation.update';

    /**
     * Specification
     * - This events will be used for spy_navigation entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_NAVIGATION_DELETE = 'Entity.spy_navigation.delete';

    /**
     * Specification
     * - This events will be used for spy_navigation_node entity creation
     *
     * @api
     */
    public const ENTITY_SPY_NAVIGATION_NODE_CREATE = 'Entity.spy_navigation_node.create';

    /**
     * Specification
     * - This events will be used for spy_navigation_node entity changes
     *
     * @api
     */
    public const ENTITY_SPY_NAVIGATION_NODE_UPDATE = 'Entity.spy_navigation_node.update';

    /**
     * Specification
     * - This events will be used for spy_navigation_node entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_NAVIGATION_NODE_DELETE = 'Entity.spy_navigation_node.delete';

    /**
     * Specification
     * - This events will be used for spy_navigation_node_localized_attributes entity creation
     *
     * @api
     */
    public const ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_CREATE = 'Entity.spy_navigation_node_localized_attributes.create';

    /**
     * Specification
     * - This events will be used for spy_navigation_node_localized_attributes entity changes
     *
     * @api
     */
    public const ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_UPDATE = 'Entity.spy_navigation_node_localized_attributes.update';

    /**
     * Specification
     * - This events will be used for spy_navigation_node_localized_attributes entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_DELETE = 'Entity.spy_navigation_node_localized_attributes.delete';
}
