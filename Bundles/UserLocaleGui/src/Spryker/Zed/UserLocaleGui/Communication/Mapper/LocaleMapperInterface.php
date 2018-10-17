<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocaleGui\Communication\Mapper;

interface LocaleMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return array
     */
    public function buildLocaleOptions(array $localeTransfers): array;
}
