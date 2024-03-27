<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Reader;

use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface;

class MerchantRelationRequestReader implements MerchantRelationRequestReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface
     */
    protected MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade
     */
    public function __construct(
        MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade
    ) {
        $this->merchantRelationRequestFacade = $merchantRelationRequestFacade;
    }

    /**
     * @param int $idMerchantRelationRequest
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer|null
     */
    public function findMerchantRelationRequestByIdMerchantRelationRequest(
        int $idMerchantRelationRequest
    ): ?MerchantRelationRequestTransfer {
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions(
                (new MerchantRelationRequestConditionsTransfer())
                    ->addIdMerchantRelationRequest($idMerchantRelationRequest)
                    ->setWithAssigneeCompanyBusinessUnitRelations(true),
            );

        $merchantRelationRequestTransfers = $this->merchantRelationRequestFacade
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer)
            ->getMerchantRelationRequests();

        return $merchantRelationRequestTransfers->offsetExists(0)
            ? $merchantRelationRequestTransfers->offsetGet(0)
            : null;
    }
}
