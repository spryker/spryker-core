<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Dependency;

use Orm\Zed\Url\Persistence\Map\SpyUrlRedirectTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;

interface UrlEvents
{

    const URL_ENTITY_PUBLISH = 'Url.entity.publish';
    const URL_ENTITY_UNPUBLISH = 'Url.entity.unpublish';

    const REDIRECT_ENTITY_PUBLISH = 'Redirect.entity.publish';
    const REDIRECT_ENTITY_UNPUBLISH = 'Redirect.entity.unpublish';

    const ENTITY_SPY_URL_CREATE = 'Entity.' . SpyUrlTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_URL_UPDATE = 'Entity.' . SpyUrlTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_URL_DELETE = 'Entity.' . SpyUrlTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_URL_REDIRECT_CREATE = 'Entity.' . SpyUrlRedirectTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_URL_REDIRECT_UPDATE = 'Entity.' . SpyUrlRedirectTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_URL_REDIRECT_DELETE = 'Entity.' . SpyUrlRedirectTableMap::TABLE_NAME . '.delete';

}
