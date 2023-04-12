<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\ServicePointCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointCollectionResponseTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;

class ServicePointsBackendApiToServicePointFacadeBridge implements ServicePointsBackendApiToServicePointFacadeInterface
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
     * @param \Generated\Shared\Transfer\ServicePointCriteriaTransfer $servicePointCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    public function getServicePointCollection(
        ServicePointCriteriaTransfer $servicePointCriteriaTransfer
    ): ServicePointCollectionTransfer {
        return $this->servicePointFacade->getServicePointCollection($servicePointCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointCollectionRequestTransfer $servicePointCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionResponseTransfer
     */
    public function createServicePointCollection(
        ServicePointCollectionRequestTransfer $servicePointCollectionRequestTransfer
    ): ServicePointCollectionResponseTransfer {
        return $this->servicePointFacade->createServicePointCollection($servicePointCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointCollectionRequestTransfer $servicePointCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionResponseTransfer
     */
    public function updateServicePointCollection(
        ServicePointCollectionRequestTransfer $servicePointCollectionRequestTransfer
    ): ServicePointCollectionResponseTransfer {
        return $this->servicePointFacade->updateServicePointCollection($servicePointCollectionRequestTransfer);
    }
}
