<?php


namespace Spryker\CartCodesRestApi\src\Spryker\Client\CartCodesRestApi\Zed;

use Generated\Shared\Transfer\AddCandidateRequestTransfer;
use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Spryker\Client\CartCodesRestApi\Dependency\Client\CartCodesRestApiToZedRequestClientInterface;

class CartCodesRestApiStub implements CartCodesRestApiStubInterface
{
    /**
     * @var \Spryker\Client\CartCodesRestApi\Dependency\Client\CartCodesRestApiToZedRequestClientInterface
     */
    protected $zedStubClient;

    /**
     * @param \Spryker\Client\CartCodesRestApi\Dependency\Client\CartCodesRestApiToZedRequestClientInterface $zedStubClient
     */
    public function __construct(CartCodesRestApiToZedRequestClientInterface $zedStubClient)
    {
        $this->zedStubClient = $zedStubClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AddCandidateRequestTransfer $addCandidateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function addCandidate(AddCandidateRequestTransfer $addCandidateRequestTransfer): CartCodeOperationResultTransfer
    {
        /** @var \Generated\Shared\Transfer\CartCodeOperationResultTransfer $cartCodeOperationResultTransfer */
        $cartCodeOperationResultTransfer = $this->zedStubClient->call('/cart-codes-rest-api/gateway/add-candidate', $addCandidateRequestTransfer);

        return $cartCodeOperationResultTransfer;
    }
}
