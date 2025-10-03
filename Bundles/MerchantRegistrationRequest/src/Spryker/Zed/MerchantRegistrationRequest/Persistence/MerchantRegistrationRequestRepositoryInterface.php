<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Persistence;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;

interface MerchantRegistrationRequestRepositoryInterface
{
    public function findMerchantRegistrationRequestById(int $idMerchantRegistrationRequest): ?MerchantRegistrationRequestTransfer;

    public function isEmailAlreadyInUse(string $email): bool;

    public function isCompanyNameAlreadyInUse(string $companyName): bool;
}
