<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\Validator;

use Generated\Shared\Transfer\RestAddressTransfer;

class CompanyBusinessUnitAddressIdValidator implements CompanyBusinessUnitAddressIdValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     *
     * @return bool
     */
    public function validateCompanyBusinessUnitAddressIdProvided(RestAddressTransfer $restAddressTransfer): bool
    {
        return $restAddressTransfer->getIdCompanyBusinessUnitAddress() !== null;
    }
}
