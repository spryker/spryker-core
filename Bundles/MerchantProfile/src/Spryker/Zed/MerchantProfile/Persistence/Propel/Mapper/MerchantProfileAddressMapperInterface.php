<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress;
use Propel\Runtime\Collection\ObjectCollection;

interface MerchantProfileAddressMapperInterface
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
    ): SpyMerchantProfileAddress;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress[] $merchantProfileAddressEntities
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MerchantProfileAddressTransfer[]
     */
    public function mapMerchantProfileAddressEntityCollectionToMerchantProfileAddressTransfers(
        ObjectCollection $merchantProfileAddressEntities
    ): ArrayObject;

    /**
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress $merchantProfileAddressEntity
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function mapMerchantProfileAddressEntityToMerchantProfileAddressTransfer(
        SpyMerchantProfileAddress $merchantProfileAddressEntity,
        MerchantProfileAddressTransfer $merchantProfileAddressTransfer
    ): MerchantProfileAddressTransfer;
}
