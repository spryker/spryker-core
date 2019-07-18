<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Dependency;

interface CmsBlockCategoryConnectorEvents
{
    /**
     * Specification
     * - This events will be used for CmsBlockCategory connector publishing
     *
     * @api
     */
    public const CMS_BLOCK_CATEGORY_CONNECTOR_PUBLISH = 'CmsBlockCategory.connector.publish';

    /**
     * Specification
     * - This events will be used for CmsBlockCategory connector un-publishing
     *
     * @api
     */
    public const CMS_BLOCK_CATEGORY_CONNECTOR_UNPUBLISH = 'CmsBlockCategory.connector.unpublish';

    /**
     * Specification
     * - This events will be used for spy_cms_block_category_connector entity creation
     *
     * @api
     */
    public const ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_CREATE = 'Entity.spy_cms_block_category_connector.create';

    /**
     * Specification
     * - This events will be used for spy_cms_block_category_connector entity update
     *
     * @api
     */
    public const ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_UPDATE = 'Entity.spy_cms_block_category_connector.update';

    /**
     * Specification
     * - This events will be used for spy_cms_block_category_connector entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_DELETE = 'Entity.spy_cms_block_category_connector.delete';

    /**
     * Specification
     * - This events will be used for spy_cms_block_category_position entity create
     *
     * @api
     */
    public const ENTITY_SPY_CMS_BLOCK_CATEGORY_POSITION_CREATE = 'Entity.spy_cms_block_category_position.create';

    /**
     * Specification
     * - This events will be used for spy_cms_block_category_position entity update
     *
     * @api
     */
    public const ENTITY_SPY_CMS_BLOCK_CATEGORY_POSITION_UPDATE = 'Entity.spy_cms_block_category_position.update';

    /**
     * Specification
     * - This events will be used for spy_cms_block_category_position entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_CMS_BLOCK_CATEGORY_POSITION_DELETE = 'Entity.spy_cms_block_category_position.delete';
}
