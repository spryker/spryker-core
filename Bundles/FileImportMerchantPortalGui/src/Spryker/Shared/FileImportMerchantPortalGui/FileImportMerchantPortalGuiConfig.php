<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Shared\FileImportMerchantPortalGui;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class FileImportMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const FILE_TYPE_DATA_IMPORT = 'data-import';

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
