<?php

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
     * @param AddCandidateRequestTransfer $addCandidateRequestTransfer
     *
     * @return CartCodeOperationResultTransfer
     */
    public function addCandidateAction(AddCandidateRequestTransfer $addCandidateRequestTransfer): CartCodeOperationResultTransfer
    {
        $quoteTransfer = $addCandidateRequestTransfer->getQuote();
        $voucherCode = $addCandidateRequestTransfer->getVoucherCode();

        return $this->getFacade()->addCandidate($quoteTransfer, $voucherCode);
    }
}
