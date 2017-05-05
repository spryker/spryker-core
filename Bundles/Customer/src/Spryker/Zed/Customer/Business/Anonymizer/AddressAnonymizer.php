<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Anonymizer;


use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Propel\Runtime\Util\PropelDateTime;
use Spryker\Service\UtilText\UtilTextService;

class AddressAnonymizer implements AddressAnonymizerInterface
{
    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function process(AddressTransfer $addressTransfer)
    {
        $addressTransfer->setAnonymizedAt(new \DateTime());

        $addressTransfer->setFirstName('');
        $addressTransfer->setLastName('');
        $addressTransfer->setSalutation(null);
        $addressTransfer->setAddress1(null);
        $addressTransfer->setAddress2(null);
        $addressTransfer->setAddress3(null);
        $addressTransfer->setCompany(null);
        $addressTransfer->setCity(null);
        $addressTransfer->setZipCode(null);
        $addressTransfer->setPhone(null);

        return $addressTransfer;
    }
}