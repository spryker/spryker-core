<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\QuoteApproval;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class QuoteApprovalConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    public const STATUS_WAITING = 'waiting';
    /**
     * @var string
     */
    public const STATUS_APPROVED = 'approved';
    /**
     * @var string
     */
    public const STATUS_DECLINED = 'declined';

    /**
     * @var string
     */
    public const PERMISSION_CONTEXT_CENT_AMOUNT = 'PERMISSION_CONTEXT_CENT_AMOUNT';
    /**
     * @var string
     */
    public const PERMISSION_CONTEXT_STORE_NAME = 'PERMISSION_CONTEXT_STORE_NAME';
    /**
     * @var string
     */
    public const PERMISSION_CONTEXT_CURRENCY_CODE = 'PERMISSION_CONTEXT_CURRENCY_CODE';

    /**
     * @api
     *
     * @return array<string>
     */
    public function getRequiredQuoteFieldsForApprovalProcess(): array
    {
        return [];
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement. BC-reason only.
     *
     * @return bool
     */
    public function isShipmentPriceIncludedInQuoteApprovalPermissionCheck(): bool
    {
        return false;
    }
}
