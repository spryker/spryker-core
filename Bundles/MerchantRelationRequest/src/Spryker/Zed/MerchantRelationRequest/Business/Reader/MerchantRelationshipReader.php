<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Reader;

use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantRelationshipFacadeInterface;

class MerchantRelationshipReader implements MerchantRelationshipReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantRelationshipFacadeInterface
     */
    protected MerchantRelationRequestToMerchantRelationshipFacadeInterface $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct(
        MerchantRelationRequestToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
    ) {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
    }

    /**
     * @param list<string> $merchantRelationRequestUuids
     *
     * @return array<string, list<\Generated\Shared\Transfer\MerchantRelationshipTransfer>>
     */
    public function getMerchantRelationshipsGroupedByMerchantRelationshipRequestUuid(array $merchantRelationRequestUuids): array
    {
        $merchantRelationshipsGroupedByMerchantRelationRequestUuid = [];
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->setMerchantRelationRequestUuids($merchantRelationRequestUuids);

        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);

        /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
        $merchantRelationshipCollectionTransfer = $this->merchantRelationshipFacade
            ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);

        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $merchantRelationRequestUuid = $merchantRelationshipTransfer->getMerchantRelationRequestUuidOrFail();
            $merchantRelationshipsGroupedByMerchantRelationRequestUuid[$merchantRelationRequestUuid][] = $merchantRelationshipTransfer;
        }

        return $merchantRelationshipsGroupedByMerchantRelationRequestUuid;
    }
}
