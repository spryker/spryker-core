<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress;
use Propel\Runtime\Collection\ObjectCollection;

class MerchantProfileAddressMapper implements MerchantProfileAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress $merchantProfileAddressEntity
     *
     * @return \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress
     */
    public function mapMerchantProfileAddressTransferToMerchantProfileAddressEntity(
        MerchantProfileAddressTransfer $merchantProfileAddressTransfer,
        SpyMerchantProfileAddress $merchantProfileAddressEntity
    ): SpyMerchantProfileAddress {
        $merchantProfileAddressEntity->fromArray(
            $merchantProfileAddressTransfer->modifiedToArray(false)
        );

        return $merchantProfileAddressEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress[] $merchantProfileAddressEntities
     * @param \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer $merchantProfileAddressCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer
     */
    public function mapMerchantProfileAddressEntityCollectionToMerchantProfileAddressCollectionTransfer(
        ObjectCollection $merchantProfileAddressEntities,
        MerchantProfileAddressCollectionTransfer $merchantProfileAddressCollectionTransfer
    ): MerchantProfileAddressCollectionTransfer {
        foreach ($merchantProfileAddressEntities as $merchantProfileAddressEntity) {
            $merchantProfileAddressCollectionTransfer->addAddress($this->mapMerchantProfileAddressEntityToMerchantProfileAddressTransfer(
                $merchantProfileAddressEntity,
                new MerchantProfileAddressTransfer()
            ));
        }

        return $merchantProfileAddressCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress $merchantProfileAddressEntity
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function mapMerchantProfileAddressEntityToMerchantProfileAddressTransfer(
        SpyMerchantProfileAddress $merchantProfileAddressEntity,
        MerchantProfileAddressTransfer $merchantProfileAddressTransfer
    ): MerchantProfileAddressTransfer {
        $merchantProfileAddressTransfer->fromArray(
            $merchantProfileAddressEntity->toArray(),
            true
        );

        if ($merchantProfileAddressEntity->getSpyCountry()) {
            $merchantProfileAddressTransfer->setCountryName($merchantProfileAddressEntity->getSpyCountry()->getName());
        }

        return $merchantProfileAddressTransfer;
    }
}
