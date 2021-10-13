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
     * @var string
     */
    public const ENTITY_SPY_URL_CREATE = 'Entity.spy_url.create';

    /**
     * Specification
     * - This events will be used for Url publishing
     *
     * @api
     * @var string
     */
    public const URL_PUBLISH = 'Url.publish';

    /**
     * Specification
     * - This events will be used for Url un-publishing
     *
     * @api
     * @var string
     */
    public const URL_UNPUBLISH = 'Url.unpublish';

    /**
     * Specification
     * - This events will be used for spy_url entity changes
     *
     * @api
     * @var string
     */
    public const ENTITY_SPY_URL_UPDATE = 'Entity.spy_url.update';

    /**
     * Specification
     * - This events will be used for spy_url entity deletion
     *
     * @api
     * @var string
     */
    public const ENTITY_SPY_URL_DELETE = 'Entity.spy_url.delete';

    /**
     * Specification
     * - This events will be used for spy_url_redirect entity creation
     *
     * @api
     * @var string
     */
    public const ENTITY_SPY_URL_REDIRECT_CREATE = 'Entity.spy_url_redirect.create';

    /**
     * Specification
     * - This events will be used for spy_url_redirect entity changes
     *
     * @api
     * @var string
     */
    public const ENTITY_SPY_URL_REDIRECT_UPDATE = 'Entity.spy_url_redirect.update';

    /**
     * Specification
     * - This events will be used for spy_url_redirect entity deletion
     *
     * @api
     * @var string
     */
    public const ENTITY_SPY_URL_REDIRECT_DELETE = 'Entity.spy_url_redirect.delete';
}
