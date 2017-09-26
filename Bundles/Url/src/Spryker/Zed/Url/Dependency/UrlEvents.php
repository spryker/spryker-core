<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Dependency;

interface UrlEvents
{

    const URL_ENTITY_PUBLISH = 'Url.entity.publish';
    const URL_ENTITY_UNPUBLISH = 'Url.entity.unpublish';

    const REDIRECT_ENTITY_PUBLISH = 'Redirect.entity.publish';
    const REDIRECT_ENTITY_UNPUBLISH = 'Redirect.entity.unpublish';

    const ENTITY_SPY_URL_CREATE = 'Entity.spy_url.create';
    const ENTITY_SPY_URL_UPDATE = 'Entity.spy_url.update';
    const ENTITY_SPY_URL_DELETE = 'Entity.spy_url.delete';

    const ENTITY_SPY_URL_REDIRECT_CREATE = 'Entity.spy_url_redirect.create';
    const ENTITY_SPY_URL_REDIRECT_UPDATE = 'Entity.spy_url_redirect.update';
    const ENTITY_SPY_URL_REDIRECT_DELETE = 'Entity.spy_url_redirect.delete';

}
