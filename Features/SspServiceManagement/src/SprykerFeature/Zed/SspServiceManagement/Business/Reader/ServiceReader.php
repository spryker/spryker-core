<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business\Reader;

use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface;

class ServiceReader implements ServiceReaderInterface
{
    /**
     * @var \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface
     */
    protected $sspServiceManagementRepository;

    /**
     * @param \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface $sspServiceManagementRepository
     */
    public function __construct(SspServiceManagementRepositoryInterface $sspServiceManagementRepository)
    {
        $this->sspServiceManagementRepository = $sspServiceManagementRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCollectionTransfer
     */
    public function getServiceCollection(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer
    {
        return $this->sspServiceManagementRepository->getServiceCollection($sspServiceCriteriaTransfer);
    }
}
