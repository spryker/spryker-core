<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRelationship\Zed;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Spryker\Client\MerchantRelationship\Dependency\Client\MerchantRelationshipToZedRequestClientInterface;

class MerchantRelationshipStub implements MerchantRelationshipStubInterface
{
    /**
     * @var \Spryker\Client\MerchantRelationship\Dependency\Client\MerchantRelationshipToZedRequestClientInterface
     */
    protected MerchantRelationshipToZedRequestClientInterface $zedRequestClient;

    /**
     * @param \Spryker\Client\MerchantRelationship\Dependency\Client\MerchantRelationshipToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(MerchantRelationshipToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\MerchantRelationship\Communication\Controller\GatewayController::getMerchantRelationshipCollectionAction()
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function getMerchantRelationshipCollection(
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): MerchantRelationshipCollectionTransfer {
        /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
        $merchantRelationshipCollectionTransfer = $this->zedRequestClient->call(
            '/merchant-relationship/gateway/get-merchant-relationship-collection',
            $merchantRelationshipCriteriaTransfer,
        );

        return $merchantRelationshipCollectionTransfer;
    }
}
