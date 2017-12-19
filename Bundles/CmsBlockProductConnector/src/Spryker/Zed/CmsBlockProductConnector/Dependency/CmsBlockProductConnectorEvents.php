<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Dependency;

interface CmsBlockProductConnectorEvents
{

    /**
     * Specification
     * - This events will be used for spy_cms_block_product entity creation
     *
     * @api
     */
    const ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_CREATE = 'Entity.spy_cms_block_product_connector.create';

    /**
     * Specification
     * - This events will be used for spy_cms_block_product entity update
     *
     * @api
     */
    const ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_UPDATE = 'Entity.spy_cms_block_product_connector.update';

    /**
     * Specification
     * - This events will be used for spy_cms_block_product entity deletion
     *
     * @api
     */
    const ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_DELETE = 'Entity.spy_cms_block_product_connector.delete';
}
