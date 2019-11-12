<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Communication\Controller;

use Generated\Shared\Transfer\AddCandidateRequestTransfer;
use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CartCodesRestApi\Business\CartCodesRestApiFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\AddCandidateRequestTransfer $addCandidateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function addCandidateAction(AddCandidateRequestTransfer $addCandidateRequestTransfer): CartCodeOperationResultTransfer
    {
        $quoteTransfer = $addCandidateRequestTransfer->getQuote();
        $voucherCode = $addCandidateRequestTransfer->getVoucherCode();

        return $this->getFacade()->addCandidate($quoteTransfer, $voucherCode);
    }
}
