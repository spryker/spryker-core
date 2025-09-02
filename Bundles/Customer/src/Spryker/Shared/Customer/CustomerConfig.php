<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Customer;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class CustomerConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    public const ANONYMOUS_SESSION_KEY = 'anonymousID';

    /**
     * Specification:
     * - URL param specifying the locale that should be used by the target page.
     *
     * @api
     *
     * @var string
     */
    public const URL_PARAM_LOCALE = '_locale';

    /**
     * @api
     *
     * @return bool
     */
    public function isDoubleOptInEnabled(): bool
    {
        return false;
    }
}
