<?php


namespace Spryker\CartCodesRestApi\src\Spryker\Client\CartCodesRestApi\Zed;


use Generated\Shared\Transfer\AddCandidateRequestTransfer;
use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Spryker\Client\CartCodesRestApi\Dependency\Client\CartCodesRestApiToZedRequestClientInterface;
use Spryker\Client\WishlistsRestApi\Dependency\Client\WishlistsRestApiToZedRequestClientInterface;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class CartCodesRestApiStub implements CartCodesRestApiStubInterface
{
    /**
     * @var CartCodesRestApiToZedRequestClientInterface
     */
    protected $zedStubClient;

    /**
     * @param CartCodesRestApiToZedRequestClientInterface $zedStubClient
     */
    public function __construct(CartCodesRestApiToZedRequestClientInterface $zedStubClient)
    {
        $this->zedStubClient = $zedStubClient;
    }

    /**
     * @param AddCandidateRequestTransfer $addCandidateRequestTransfer
     *
     * @return CartCodeOperationResultTransfer
     */
    public function addCandidate(AddCandidateRequestTransfer $addCandidateRequestTransfer): CartCodeOperationResultTransfer
    {
        /** @var \Generated\Shared\Transfer\CartCodeOperationResultTransfer $cartCodeOperationResultTransfer */
        $cartCodeOperationResultTransfer = $this->zedStubClient->call('/cart-codes-rest-api/gateway/add-candidate', $addCandidateRequestTransfer);

        return $cartCodeOperationResultTransfer;
    }
}
