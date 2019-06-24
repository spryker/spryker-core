<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Persistence;

interface LocaleRepositoryInterface
{
    /**
     * @param string[] $localeNames
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleTransfersByLocaleNames(array $localeNames): array;
}
