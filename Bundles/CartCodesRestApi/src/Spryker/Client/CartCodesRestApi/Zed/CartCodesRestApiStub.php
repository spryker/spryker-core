<?php


namespace Spryker\CartCodesRestApi\src\Spryker\Client\CartCodesRestApi\Zed;


use Generated\Shared\Transfer\AddCandidateRequestTransfer;
use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class CartCodesRestApiStub extends ZedRequestStub implements CartCodesRestApiStubInterface
{
    /**
     * @param AddCandidateRequestTransfer $addCandidateRequestTransfer
     *
     * @return CartCodeOperationResultTransfer
     */
    public function addCandidate(AddCandidateRequestTransfer $addCandidateRequestTransfer): CartCodeOperationResultTransfer
    {
        /** @var \Generated\Shared\Transfer\CartCodeOperationResultTransfer $cartCodeOperationResultTransfer */
        $cartCodeOperationResultTransfer = $this->zedStub->call('/cart-codes-rest-api/gateway/add-candidate', $addCandidateRequestTransfer);

        return $cartCodeOperationResultTransfer;
    }
}
