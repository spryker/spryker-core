<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\Processor\TransferBuilder;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface MultiFactorAuthTransferBuilderInterface
{
    /**
     * @param string $multiFactorAuthCode
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function buildMultiFactorAuthCodeTransfer(string $multiFactorAuthCode): MultiFactorAuthCodeTransfer;

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
    ): MultiFactorAuthTransfer;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function buildCustomerTransfer(RestRequestInterface $restRequest): CustomerTransfer;
}
