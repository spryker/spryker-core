<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRelationRequest\Zed;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Spryker\Client\MerchantRelationRequest\Dependency\Client\MerchantRelationRequestToZedRequestClientInterface;

class MerchantRelationRequestStub implements MerchantRelationRequestStubInterface
{
    /**
     * @var \Spryker\Client\MerchantRelationRequest\Dependency\Client\MerchantRelationRequestToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\MerchantRelationRequest\Dependency\Client\MerchantRelationRequestToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(MerchantRelationRequestToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @see \Spryker\Zed\MerchantRelationRequest\Communication\Controller\GatewayController::getMerchantRelationRequestCollectionAction()
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function getMerchantRelationRequestCollection(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCollectionTransfer {
        /** @var \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer */
        $merchantRelationRequestCollectionTransfer = $this->zedRequestClient->call(
            '/merchant-relation-request/gateway/get-merchant-relation-request-collection',
            $merchantRelationRequestCriteriaTransfer,
        );

        return $merchantRelationRequestCollectionTransfer;
    }

    /**
     * @see \Spryker\Zed\MerchantRelationRequest\Communication\Controller\GatewayController::createMerchantRelationRequestCollectionAction()
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function createMerchantRelationRequestCollection(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        /** @var \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer */
        $merchantRelationRequestCollectionResponseTransfer = $this->zedRequestClient->call(
            '/merchant-relation-request/gateway/create-merchant-relation-request-collection',
            $merchantRelationRequestCollectionRequestTransfer,
        );

        return $merchantRelationRequestCollectionResponseTransfer;
    }

    /**
     * @see \Spryker\Zed\MerchantRelationRequest\Communication\Controller\GatewayController::updateMerchantRelationRequestCollectionAction()
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function updateMerchantRelationRequestCollection(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        /** @var \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer */
        $merchantRelationRequestCollectionResponseTransfer = $this->zedRequestClient->call(
            '/merchant-relation-request/gateway/update-merchant-relation-request-collection',
            $merchantRelationRequestCollectionRequestTransfer,
        );

        return $merchantRelationRequestCollectionResponseTransfer;
    }
}
