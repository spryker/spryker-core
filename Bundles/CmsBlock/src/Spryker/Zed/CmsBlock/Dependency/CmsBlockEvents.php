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

}
