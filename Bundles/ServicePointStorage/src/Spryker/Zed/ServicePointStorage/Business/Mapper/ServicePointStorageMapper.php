<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceStorageTransfer;
use Generated\Shared\Transfer\ServiceTransfer;

class ServicePointStorageMapper implements ServicePointStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param \Generated\Shared\Transfer\ServicePointStorageTransfer $servicePointStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageTransfer
     */
    public function mapServicePointTransferToServicePointStorageTransfer(
        ServicePointTransfer $servicePointTransfer,
        ServicePointStorageTransfer $servicePointStorageTransfer
    ): ServicePointStorageTransfer {
        $servicePointStorageTransfer = $servicePointStorageTransfer->fromArray($servicePointTransfer->toArray(), true);

        $servicePointStorageTransfer->setServices(new ArrayObject());
        foreach ($servicePointTransfer->getServices() as $serviceTransfer) {
            $serviceStorageTransfer = $this->mapServiceTransferToServiceStorageTransfer($serviceTransfer, new ServiceStorageTransfer());

            $servicePointStorageTransfer->addService($serviceStorageTransfer);
        }

        return $servicePointStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     * @param \Generated\Shared\Transfer\ServiceStorageTransfer $serviceStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceStorageTransfer
     */
    protected function mapServiceTransferToServiceStorageTransfer(
        ServiceTransfer $serviceTransfer,
        ServiceStorageTransfer $serviceStorageTransfer
    ): ServiceStorageTransfer {
        return $serviceStorageTransfer->fromArray($serviceTransfer->toArray(), true)
            ->setServiceType($serviceTransfer->getServiceTypeOrFail()->getKeyOrFail());
    }
}
