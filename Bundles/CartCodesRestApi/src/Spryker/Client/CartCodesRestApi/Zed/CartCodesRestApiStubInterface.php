<?php

namespace Spryker\CartCodesRestApi\src\Spryker\Client\CartCodesRestApi\Zed;

use Generated\Shared\Transfer\AddCandidateRequestTransfer;
use Generated\Shared\Transfer\CartCodeOperationResultTransfer;

interface CartCodesRestApiStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\AddCandidateRequestTransfer $addCandidateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function addCandidate(AddCandidateRequestTransfer $addCandidateRequestTransfer): CartCodeOperationResultTransfer;
}
