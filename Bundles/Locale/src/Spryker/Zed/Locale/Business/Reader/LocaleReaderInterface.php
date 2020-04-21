<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business\Reader;

use Generated\Shared\Transfer\LocaleTransfer;

interface LocaleReaderInterface
{
    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleByName(string $localeName): LocaleTransfer;
}
