<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Dependency;

interface CompanyUserEvents
{
    /**
     * Specification
     *  - This event will be used for company_user publishing.
     *
     * @api
     */
    public const COMPANY_USER_PUBLISH = 'Entity.company_user.publish';

    /**
     * Specification
     *  - This event will be used for company_user un-publishing.
     *
     * @api
     */
    public const COMPANY_USER_UNPUBLISH = 'Entity.company_user.unpublish';

    /**
     * Specification:
     *  - This event will be used for company_user entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_COMPANY_USER_CREATE = 'Entity.spy_company_user.create';

    /**
     * Specification:
     * - This event will be used for company_user entity update.
     *
     * @api
     */
    public const ENTITY_SPY_COMPANY_USER_UPDATE = 'Entity.spy_company_user.update';

    /**
     * Specification:
     * - This event will be used for company_user entity delete.
     *
     * @api
     */
    public const ENTITY_SPY_COMPANY_USER_DELETE = 'Entity.spy_company_user.delete';
}
