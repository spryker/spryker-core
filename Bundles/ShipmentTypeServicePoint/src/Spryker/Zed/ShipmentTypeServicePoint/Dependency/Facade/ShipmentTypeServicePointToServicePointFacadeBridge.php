<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Dependency\Facade;

use Generated\Shared\Transfer\ServiceTypeCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeCriteriaTransfer;

class ShipmentTypeServicePointToServicePointFacadeBridge implements ShipmentTypeServicePointToServicePointFacadeInterface
{
    /**
     * @var \Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface
     */
    protected $servicePointFacade;

    /**
     * @param \Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface $servicePointFacade
     */
    public function __construct($servicePointFacade)
    {
        $this->servicePointFacade = $servicePointFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeCriteriaTransfer $serviceTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeCollectionTransfer
     */
    public function getServiceTypeCollection(ServiceTypeCriteriaTransfer $serviceTypeCriteriaTransfer): ServiceTypeCollectionTransfer
    {
        return $this->servicePointFacade->getServiceTypeCollection($serviceTypeCriteriaTransfer);
    }
}
