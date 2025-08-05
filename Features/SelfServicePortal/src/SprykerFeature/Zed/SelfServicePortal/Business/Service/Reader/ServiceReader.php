<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader;

use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Permission\SspServiceCustomerPermissionExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class ServiceReader implements ServiceReaderInterface
{
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository,
        protected SspServiceCustomerPermissionExpanderInterface $sspServiceCustomerPermissionExpander
    ) {
    }

    public function getSspServiceCollection(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer
    {
        $sspServiceCriteriaTransfer = $this->sspServiceCustomerPermissionExpander->expand($sspServiceCriteriaTransfer);

        return $this->selfServicePortalRepository->getSspServiceCollection($sspServiceCriteriaTransfer);
    }
}
