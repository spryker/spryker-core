<?php

namespace Spryker\CartCodesRestApi\src\Spryker\Client\CartCodesRestApi\Zed;

use Generated\Shared\Transfer\AddCandidateRequestTransfer;
use Generated\Shared\Transfer\CartCodeOperationResultTransfer;

interface CartCodesRestApiStubInterface
{
    /**
     * @param AddCandidateRequestTransfer $addCandidateRequestTransfer
     *
     * @return CartCodeOperationResultTransfer
     */
    public function addCandidate(AddCandidateRequestTransfer $addCandidateRequestTransfer): CartCodeOperationResultTransfer;
}
