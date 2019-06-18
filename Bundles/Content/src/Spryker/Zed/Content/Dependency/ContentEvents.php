<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Dependency;

interface ContentEvents
{
    /**
     * Specification
     * - This events will be used for spy_content un-publishing.
     *
     * @api
     */
    public const ENTITY_SPY_CONTENT_UNPUBLISH = 'Entity.spy_content.unpublish';

    /**
     * Specification
     * - This event will be used for spy_content entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_CONTENT_UPDATE = 'Entity.spy_content.update';

    /**
     * Specification
     * - This event will be used for spy_content entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_CONTENT_CREATE = 'Entity.spy_content.create';

    /**
     * Specification
     * - This events will be used for content publishing.
     *
     * @api
     */
    public const CONTENT_PUBLISH = 'Content.content.publish';
}
