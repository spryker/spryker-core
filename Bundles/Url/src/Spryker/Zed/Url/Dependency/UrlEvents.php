<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Dependency;

interface UrlEvents
{

    /**
     * Specification
     * - This events will be used for spy_url entity creation
     *
     * @api
     */
    const ENTITY_SPY_URL_CREATE = 'Entity.spy_url.create';

    /**
     * Specification
     * - This events will be used for spy_url entity changes
     *
     * @api
     */
    const ENTITY_SPY_URL_UPDATE = 'Entity.spy_url.update';

    /**
     * Specification
     * - This events will be used for spy_url entity deletion
     *
     * @api
     */
    const ENTITY_SPY_URL_DELETE = 'Entity.spy_url.delete';

    /**
     * Specification
     * - This events will be used for spy_url_redirect entity creation
     *
     * @api
     */
    const ENTITY_SPY_URL_REDIRECT_CREATE = 'Entity.spy_url_redirect.create';

    /**
     * Specification
     * - This events will be used for spy_url_redirect entity changes
     *
     * @api
     */
    const ENTITY_SPY_URL_REDIRECT_UPDATE = 'Entity.spy_url_redirect.update';

    /**
     * Specification
     * - This events will be used for spy_url_redirect entity deletion
     *
     * @api
     */
    const ENTITY_SPY_URL_REDIRECT_DELETE = 'Entity.spy_url_redirect.delete';

}
