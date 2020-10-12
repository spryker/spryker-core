<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Persistence;

use Generated\Shared\Transfer\MerchantShipmentCollectionTransfer;
use Generated\Shared\Transfer\MerchantShipmentTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantShipment\Persistence\MerchantShipmentPersistenceFactory getFactory()
 */
class MerchantShipmentRepository extends AbstractRepository implements MerchantShipmentRepositoryInterface
{
    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantShipmentCollectionTransfer
     */
    public function getMerchantShipments(string $merchantReference): MerchantShipmentCollectionTransfer
    {
        $merchantShipmentEntities = $this->getFactory()
            ->createSalesShipmentPropelQuery()
            ->findByMerchantReference($merchantReference);

        $merchantShipmentCollection = new MerchantShipmentCollectionTransfer();
        $merchantShipmentMapper = $this->getFactory()->createMerchantShipmentMapper();

        foreach ($merchantShipmentEntities as $merchantShipmentEntity) {
            $merchantShipmentCollection->addMerchantShipment(
                $merchantShipmentMapper->mapMerchantShipmentEntityToMerchantShipmentTransfer(
                    $merchantShipmentEntity,
                    new MerchantShipmentTransfer()
                )
            );
        }

        return $merchantShipmentCollection;
    }
}
