<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Translator;

use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;

interface MerchantOpeningHoursTranslatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer
     */
    public function getMerchantOpeningHoursTransferWithTranslatedNotes(
        MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer,
        string $localeName
    ): MerchantOpeningHoursStorageTransfer;
}
