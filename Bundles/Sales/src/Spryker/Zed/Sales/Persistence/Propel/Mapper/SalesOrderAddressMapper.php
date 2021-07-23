<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;

class SalesOrderAddressMapper implements SalesOrderAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    public function mapAddressTransferToSalesOrderAddressEntity(AddressTransfer $addressTransfer): SpySalesOrderAddress
    {
        $salesOrderAddressEntity = new SpySalesOrderAddress();
        $salesOrderAddressEntity->fromArray($addressTransfer->toArray());

        return $salesOrderAddressEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $salesOrderAddressEntity
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function mapAddressEntityToAddressTransfer(
        AddressTransfer $addressTransfer,
        SpySalesOrderAddress $salesOrderAddressEntity
    ): AddressTransfer {
        $addressTransfer->fromArray($salesOrderAddressEntity->toArray(), true);

        $countryEntity = $salesOrderAddressEntity->getCountry();
        $addressTransfer->setIso2Code($countryEntity->getIso2Code());
        $countryTransfer = (new CountryTransfer())->fromArray($countryEntity->toArray(), true);
        $addressTransfer->setCountry($countryTransfer);

        return $addressTransfer;
    }
}
