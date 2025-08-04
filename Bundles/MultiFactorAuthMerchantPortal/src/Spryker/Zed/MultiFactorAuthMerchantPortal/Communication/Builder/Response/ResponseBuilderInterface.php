<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Builder\Response;

use Generated\Shared\Transfer\ZedUiFormRequestActionTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;

interface ResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer
     * @param string $responseType
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function buildResponse(ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer, string $responseType): JsonResponse;
}
