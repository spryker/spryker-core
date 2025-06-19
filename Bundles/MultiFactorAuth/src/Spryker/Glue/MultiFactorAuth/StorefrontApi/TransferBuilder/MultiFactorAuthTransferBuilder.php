<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\StorefrontApi\TransferBuilder;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;

class MultiFactorAuthTransferBuilder implements MultiFactorAuthTransferBuilderInterface
{
    /**
     * @param string $multiFactorAuthCode
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function buildMultiFactorAuthCodeTransfer(string $multiFactorAuthCode): MultiFactorAuthCodeTransfer
    {
        return (new MultiFactorAuthCodeTransfer())->setCode($multiFactorAuthCode);
    }

    /**
     * @param string $multiFactorAuthType
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer|null $multiFactorAuthCodeTransfer
     * @param int|null $status
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function buildMultiFactorAuthTransfer(
        string $multiFactorAuthType,
        CustomerTransfer $customerTransfer,
        ?MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer = null,
        ?int $status = null
    ): MultiFactorAuthTransfer {
        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setType($multiFactorAuthType)
            ->setCustomer($customerTransfer);

        if ($multiFactorAuthCodeTransfer !== null) {
            $multiFactorAuthTransfer->setMultiFactorAuthCode($multiFactorAuthCodeTransfer);
        }

        if ($status !== null) {
            $multiFactorAuthTransfer->setStatus($status);
        }

        return $multiFactorAuthTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function buildCustomerTransfer(GlueRequestTransfer $glueRequestTransfer): CustomerTransfer
    {
        return (new CustomerTransfer())
            ->setIdCustomer($glueRequestTransfer->getRequestCustomer()?->getSurrogateIdentifierOrFail());
    }
}
