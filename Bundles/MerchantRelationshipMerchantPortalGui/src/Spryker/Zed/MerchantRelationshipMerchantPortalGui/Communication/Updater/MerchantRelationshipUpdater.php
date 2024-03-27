<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Updater;

use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface;

class MerchantRelationshipUpdater implements MerchantRelationshipUpdaterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface
     */
    protected MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct(MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade)
    {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipResponseTransfer
     */
    public function updateMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipResponseTransfer {
        $merchantRelationshipRequestTransfer = (new MerchantRelationshipRequestTransfer())
            ->setMerchantRelationship($merchantRelationshipTransfer);

        /** @var \Generated\Shared\Transfer\MerchantRelationshipResponseTransfer $merchantRelationshipResponseTransfer */
        $merchantRelationshipResponseTransfer = $this->merchantRelationshipFacade->updateMerchantRelationship(
            $merchantRelationshipTransfer,
            $merchantRelationshipRequestTransfer,
        );

        return $merchantRelationshipResponseTransfer;
    }
}
