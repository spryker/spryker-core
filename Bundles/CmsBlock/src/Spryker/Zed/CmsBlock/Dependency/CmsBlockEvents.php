<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Dependency;

interface CmsBlockEvents
{
    /**
     * Specification
     * - This events will be used for CmsBlock publishing
     *
     * @api
     */
    const CMS_BLOCK_PUBLISH = 'CmsBlock.block.publish';

    /**
     * Specification
     * - This events will be used for CmsBlock un-publishing
     *
     * @api
     */
    const CMS_BLOCK_UNPUBLISH = 'CmsBlock.block.unpublish';

    /**
     * Specification
     * - This events will be used for spy_cms_block_glossary_key_mapping entity creation
     *
     * @api
     */
    const ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_CREATE = 'Entity.spy_cms_block_glossary_key_mapping.create';

    /**
     * Specification
     * - This events will be used for spy_cms_block_glossary_key_mapping entity changes
     *
     * @api
     */
    const ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_UPDATE = 'Entity.spy_cms_block_glossary_key_mapping.update';

    /**
     * Specification
     * - This events will be used for spy_cms_block_glossary_key_mapping entity deletion
     *
     * @api
     */
    const ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_DELETE = 'Entity.spy_cms_block_glossary_key_mapping.delete';

    /**
     * Specification
     * - This events will be used for spy_cms_block entity update
     *
     * @api
     */
    const ENTITY_SPY_CMS_BLOCK_UPDATE = 'Entity.spy_cms_block.update';

    /**
     * Specification
     * - This events will be used for spy_cms_block entity deletion
     *
     * @api
     */
    const ENTITY_SPY_CMS_BLOCK_DELETE = 'Entity.spy_cms_block.delete';

    /**
     * Specification:
     * - Represents spy_cms_block_store entity creation.
     *
     * @api
     */
    const ENTITY_SPY_CMS_BLOCK_STORE_CREATE = 'Entity.spy_cms_block_store.create';

    /**
     * Specification:
     * - Represents spy_cms_block_store entity changes.
     *
     * @api
     */
    const ENTITY_SPY_CMS_BLOCK_STORE_UPDATE = 'Entity.spy_cms_block_store.update';

    /**
     * Specification:
     * - Represents spy_cms_block_store entity deletion.
     *
     * @api
     */
    const ENTITY_SPY_CMS_BLOCK_STORE_DELETE = 'Entity.spy_cms_block_store.delete';
}
