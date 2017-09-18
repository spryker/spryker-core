<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsVersionTableMap;

interface CmsEvents
{

    const CMS_PAGE_PUBLISH = 'Cms.page.publish';
    const CMS_PAGE_UNPUBLISH = 'Cms.page.unpublish';

    const ENTITY_SPY_CMS_PAGE_CREATE = 'Entity.' . SpyCmsPageTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_CMS_PAGE_UPDATE = 'Entity.' . SpyCmsPageTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_CMS_PAGE_DELETE = 'Entity.' . SpyCmsPageTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_CMS_VERSION_CREATE = 'Entity.' . SpyCmsVersionTableMap::TABLE_NAME . '.create';

}
