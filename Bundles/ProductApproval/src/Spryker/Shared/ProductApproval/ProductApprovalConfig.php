<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductApproval;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ProductApprovalConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Product `Waiting for approval` status name.
     *
     * @api
     *
     * @var string
     */
    public const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * Specification:
     * - Product `Approved` status name.
     *
     * @api
     *
     * @var string
     */
    public const STATUS_APPROVED = 'approved';

    /**
     * Specification:
     * - Product `Denied` status name.
     *
     * @api
     *
     * @var string
     */
    public const STATUS_DENIED = 'denied';

    /**
     * Specification:
     * - Product `Draft` status name.
     *
     * @api
     *
     * @var string
     */
    public const STATUS_DRAFT = 'draft';
}
