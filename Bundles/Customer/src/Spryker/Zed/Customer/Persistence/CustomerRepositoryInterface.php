<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Persistence;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollection(CustomerCollectionTransfer $customerCollectionTransfer): CustomerCollectionTransfer;

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerByReference(string $customerReference): ?CustomerTransfer;

    /**
     * @param int $idCustomerAddress
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function findCustomerAddressById(int $idCustomerAddress): ?AddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function findAddressByAddressData(AddressTransfer $addressTransfer): ?AddressTransfer;

    /**
     * @return array
     */
    public function getAllSalutations(): array;
}
