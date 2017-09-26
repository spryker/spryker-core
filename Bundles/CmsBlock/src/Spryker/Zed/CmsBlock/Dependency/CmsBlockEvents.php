<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Dependency;

interface CmsBlockEvents
{

    const CMS_BLOCK_PUBLISH = 'Cms.block.publish';
    const CMS_BLOCK_UNPUBLISH = 'Cms.block.unpublish';

    const ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_UPDATE = 'Entity.spy_cms_block_glossary_key_mapping.update';
    const ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_DELETE = 'Entity.spy_cms_block_glossary_key_mapping.delete';

}
