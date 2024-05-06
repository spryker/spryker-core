<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;

class MerchantProfileAddressMapper implements MerchantProfileAddressMapperInterface
{
    /**
     * @var string
     */
    protected const COL_MERCHANT_REFERENCE = 'merchant_reference';

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
            $merchantProfileAddressTransfer->modifiedToArray(false),
        );

        return $merchantProfileAddressEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress> $merchantProfileAddressEntities
     * @param \ArrayObject<int, \Generated\Shared\Transfer\MerchantProfileAddressTransfer> $merchantProfileAddressTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\MerchantProfileAddressTransfer>
     */
    public function mapMerchantProfileAddressEntityCollectionToMerchantProfileAddressTransfers(
        ObjectCollection $merchantProfileAddressEntities,
        ArrayObject $merchantProfileAddressTransfers
    ): ArrayObject {
        foreach ($merchantProfileAddressEntities as $merchantProfileAddressEntity) {
            $merchantProfileAddressTransfers->append(
                $this->mapMerchantProfileAddressEntityToMerchantProfileAddressTransfer(
                    $merchantProfileAddressEntity,
                    new MerchantProfileAddressTransfer(),
                ),
            );
        }

        return $merchantProfileAddressTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $merchantProfileAddressEntities
     * @param array $merchantProfileAddressTransfers
     *
     * @return array
     */
    public function mapMerchantProfileAddressEntityCollectionToMerchantProfileAddressTransfersIndexedByMerchantReference(
        Collection $merchantProfileAddressEntities,
        array $merchantProfileAddressTransfers
    ): array {
        /** @var \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress $merchantProfileAddressEntity */
        foreach ($merchantProfileAddressEntities as $merchantProfileAddressEntity) {
            $merchantReference = $merchantProfileAddressEntity->getVirtualColumn(static::COL_MERCHANT_REFERENCE);

            $merchantProfileAddressTransfers[$merchantReference][] = $this->mapMerchantProfileAddressEntityToMerchantProfileAddressTransfer(
                $merchantProfileAddressEntity,
                new MerchantProfileAddressTransfer(),
            );
        }

        return $merchantProfileAddressTransfers;
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
            true,
        );

        if ($merchantProfileAddressEntity->getSpyCountry() !== null) {
            $merchantProfileAddressTransfer->setCountryName($merchantProfileAddressEntity->getSpyCountry()->getName());
            $merchantProfileAddressTransfer->setIso2Code($merchantProfileAddressEntity->getSpyCountry()->getIso2Code());
        }

        return $merchantProfileAddressTransfer;
    }
}
