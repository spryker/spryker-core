<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Translator;

interface MerchantOpeningHoursTranslatorInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer> $merchantOpeningHoursStorageTransfers
     * @param string $localeName
     *
     * @return array<\Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer>
     */
    public function translateMerchantOpeningHoursTransfers(array $merchantOpeningHoursStorageTransfers, string $localeName): array;
}
