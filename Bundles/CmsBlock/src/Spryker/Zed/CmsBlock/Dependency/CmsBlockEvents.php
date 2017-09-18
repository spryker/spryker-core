<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Dependency;

use Orm\Zed\Cms\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockGlossaryKeyMappingTableMap;

interface CmsBlockEvents
{

    const CMS_BLOCK_PUBLISH = 'Cms.block.publish';
    const CMS_BLOCK_UNPUBLISH = 'Cms.block.unpublish';

    const ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_UPDATE = 'Entity.' . SpyCmsBlockGlossaryKeyMappingTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_DELETE = 'Entity.' . SpyCmsBlockGlossaryKeyMappingTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_CMS_PAGE_UPDATE = 'Entity.' . SpyCmsBlockTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_CMS_PAGE_CREATE = 'Entity.' . SpyCmsBlockTableMap::TABLE_NAME . '.create';

}
