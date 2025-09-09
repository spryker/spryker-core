<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\DataImportMerchant;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class DataImportMerchantConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    public const STATUS_PENDING = 'pending';

    /**
     * @var string
     */
    public const STATUS_IN_PROGRESS = 'in_progress';

    /**
     * @var string
     */
    public const STATUS_SUCCESSFUL = 'successful';

    /**
     * @var string
     */
    public const STATUS_FAILED = 'failed';

    /**
     * @var string
     */
    public const STATUS_IMPORTED_WITH_ERRORS = 'imported_with_errors';
}
