<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Anonymizer;


use Generated\Shared\Transfer\AddressTransfer;

interface AddressAnonymizerInterface
{
    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return mixed
     */
    public function process(AddressTransfer $addressTransfer);
}