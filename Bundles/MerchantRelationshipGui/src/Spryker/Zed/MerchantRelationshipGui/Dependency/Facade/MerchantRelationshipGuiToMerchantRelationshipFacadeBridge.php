<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

class MerchantRelationshipGuiToMerchantRelationshipFacadeBridge implements MerchantRelationshipGuiToMerchantRelationshipFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct($merchantRelationshipFacade)
    {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer|null $merchantRelationshipRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|\Generated\Shared\Transfer\MerchantRelationshipResponseTransfer
     */
    public function createMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ?MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer = null
    ) {
        return $this->merchantRelationshipFacade->createMerchantRelationship($merchantRelationshipTransfer, $merchantRelationshipRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer|null $merchantRelationshipRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|\Generated\Shared\Transfer\MerchantRelationshipResponseTransfer
     */
    public function updateMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ?MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer = null
    ) {
        return $this->merchantRelationshipFacade->updateMerchantRelationship($merchantRelationshipTransfer, $merchantRelationshipRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer|null $merchantRelationshipRequestTransfer
     *
     * @return void
     */
    public function deleteMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ?MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer = null
    ): void {
        $this->merchantRelationshipFacade->deleteMerchantRelationship($merchantRelationshipTransfer, $merchantRelationshipRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function getMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        return $this->merchantRelationshipFacade->getMerchantRelationshipById($merchantRelationshipTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): ?MerchantRelationshipTransfer
    {
        return $this->merchantRelationshipFacade->findMerchantRelationshipById($merchantRelationshipTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipFilterTransfer|null $merchantRelationshipFilterTransfer Deprecated: Use {@link \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer} instead.
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer|null $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer|array<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
     */
    public function getMerchantRelationshipCollection(
        ?MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer = null,
        ?MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer = null
    ) {
        return $this->merchantRelationshipFacade->getMerchantRelationshipCollection(
            $merchantRelationshipFilterTransfer,
            $merchantRelationshipCriteriaTransfer,
        );
    }
}
