<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationRequestConnector\Business\Reader;

use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Spryker\Zed\CommentMerchantRelationRequestConnector\Dependency\Facade\CommentMerchantRelationRequestConnectorToMerchantRelationRequestFacadeInterface;

class MerchantRelationRequestReader implements MerchantRelationRequestReaderInterface
{
    /**
     * @var \Spryker\Zed\CommentMerchantRelationRequestConnector\Dependency\Facade\CommentMerchantRelationRequestConnectorToMerchantRelationRequestFacadeInterface
     */
    protected CommentMerchantRelationRequestConnectorToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade;

    /**
     * @param \Spryker\Zed\CommentMerchantRelationRequestConnector\Dependency\Facade\CommentMerchantRelationRequestConnectorToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade
     */
    public function __construct(
        CommentMerchantRelationRequestConnectorToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade
    ) {
        $this->merchantRelationRequestFacade = $merchantRelationRequestFacade;
    }

    /**
     * @param list<string> $merchantRelationRequestUuids
     *
     * @return array<string, \Generated\Shared\Transfer\MerchantRelationRequestTransfer>
     */
    public function getMerchantRelationRequestTransfersIndexedByUuid(
        array $merchantRelationRequestUuids
    ): array {
        $merchantRelationRequestTransfersIndexedByUuid = [];
        if (!$merchantRelationRequestUuids) {
            return $merchantRelationRequestTransfersIndexedByUuid;
        }

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions(
                (new MerchantRelationRequestConditionsTransfer())
                    ->setUuids($merchantRelationRequestUuids),
            );

        $merchantRelationRequestTransfers = $this->merchantRelationRequestFacade
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer)
            ->getMerchantRelationRequests();

        foreach ($merchantRelationRequestTransfers as $merchantRelationRequestTransfer) {
            $merchantRelationRequestTransfersIndexedByUuid[$merchantRelationRequestTransfer->getUuidOrFail()] = $merchantRelationRequestTransfer;
        }

        return $merchantRelationRequestTransfersIndexedByUuid;
    }
}
