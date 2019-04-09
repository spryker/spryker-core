<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CompanyUserStorage;

interface CompanyUserStorageConfig
{
    /**
     * Specification:
     *  - Queue name as used for processing company user messages.
     *
     * @api
     */
    public const COMPANY_USER_SYNC_STORAGE_QUEUE = 'sync.storage.company_user';

    /**
     * Specification:
     *  - Queue name as used for processing company user error messages.
     *
     * @api
     */
    public const COMPANY_USER_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.company_user.error';

    /**
     * Specification:
     *  - Resource name, this will use for key generation.
     *
     * @api
     */
    public const COMPANY_USER_RESOURCE_NAME = 'company_user';
}
