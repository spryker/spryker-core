<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Dependency;

interface CmsBlockProductConnectorEvents
{
    /**
     * Specification
     * - This events will be used for CmsBlockProduct connector publishing
     *
     * @api
     */
    public const CMS_BLOCK_PRODUCT_CONNECTOR_PUBLISH = 'CmsBlockProduct.connector.publish';

    /**
     * Specification
     * - This events will be used for CmsBlockProduct connector un-publishing
     *
     * @api
     */
    public const CMS_BLOCK_PRODUCT_CONNECTOR_UNPUBLISH = 'CmsBlockProduct.connector.unpublish';
    /**
     * Specification
     * - This events will be used for spy_cms_block_product_connector entity creation
     *
     * @api
     */
    public const ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_CREATE = 'Entity.spy_cms_block_product_connector.create';

    /**
     * Specification
     * - This events will be used for spy_cms_block_product_connector entity update
     *
     * @api
     */
    public const ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_UPDATE = 'Entity.spy_cms_block_product_connector.update';

    /**
     * Specification
     * - This events will be used for spy_cms_block_product_connector entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_DELETE = 'Entity.spy_cms_block_product_connector.delete';
}
