<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency;

interface CmsEvents
{
    /**
     * Specification
     * - This events will be used for spy_cms_page entity creation
     *
     * @api
     */
    public const ENTITY_SPY_CMS_PAGE_CREATE = 'Entity.spy_cms_page.create';

    /**
     * Specification
     * - This events will be used for spy_cms_page entity changes
     *
     * @api
     */
    public const ENTITY_SPY_CMS_PAGE_UPDATE = 'Entity.spy_cms_page.update';

    /**
     * Specification
     * - This events will be used for spy_cms_page entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_CMS_PAGE_DELETE = 'Entity.spy_cms_page.delete';

    /**
     * Specification
     * - This events will be used for cms version publish
     *
     * @api
     */
    public const CMS_VERSION_PUBLISH = 'Cms.version.publish';

    /**
     * Specification
     * - This events will be used for cms version publish
     *
     * @api
     */
    public const CMS_VERSION_UNPUBLISH = 'Cms.version.unpublish';

    /**
     * Specification
     * - This events will be used for spy_cms_version entity creation
     *
     * @api
     */
    public const ENTITY_SPY_CMS_VERSION_CREATE = 'Entity.spy_cms_version.create';

    /**
     * Specification
     * - This events will be used for spy_cms_version entity changes
     *
     * @api
     */
    public const ENTITY_SPY_CMS_VERSION_UPDATE = 'Entity.spy_cms_version.update';

    /**
     * Specification
     * - This events will be used for spy_cms_version entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_CMS_VERSION_DELETE = 'Entity.spy_cms_version.delete';
}
