<?php

declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantOpeningHoursStorage\Reader\Filter;

/**
 * Provides ability to process MerchantOpeningHoursStorageReader results
 */
interface MerchantOpeningHoursStorageReaderFilterInterface
{
    /**
     * @phpstan-param array<mixed> $merchantOpeningHoursStorageData
     *
     * @phpstan-return array<mixed>
     *
     * @api
     *
     * @param array $merchantOpeningHoursStorageData
     *
     * @return array
     */
    public function filter(array $merchantOpeningHoursStorageData): array;
}
