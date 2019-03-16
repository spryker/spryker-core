<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Dependency;

interface CompanyEvents
{
    /**
     * Specification:
     * - This event will be used for company entity update.
     *
     * @api
     */
    public const ENTITY_SPY_COMPANY_UPDATE = 'Entity.spy_company.update';
}
