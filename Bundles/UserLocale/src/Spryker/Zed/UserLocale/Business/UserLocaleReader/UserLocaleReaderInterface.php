<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business\UserLocaleReader;

use Generated\Shared\Transfer\LocaleTransfer;

interface UserLocaleReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentUserLocaleTransfer(): LocaleTransfer;
}
