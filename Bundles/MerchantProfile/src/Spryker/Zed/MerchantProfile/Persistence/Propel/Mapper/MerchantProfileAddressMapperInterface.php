<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Generated\Shared\Transfer\SpyMerchantProfileAddressEntityTransfer;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress;

interface MerchantProfileAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress $spyMerchantProfileAddress
     *
     * @return \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress
     */
    public function mapMerchantProfileAddressTransferToSpyMerchantProfileAddressEntity(
        MerchantProfileAddressTransfer $merchantProfileAddressTransfer,
        SpyMerchantProfileAddress $spyMerchantProfileAddress
    ): SpyMerchantProfileAddress;

    /**
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress[] $spyMerchantProfileAddresses
     * @param \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer $merchantProfileAddressCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer|null
     */
    public function mapMerchantProfileAddressEntitiesToMerchantProfileAddressCollectionTransfer(
        array $spyMerchantProfileAddresses,
        MerchantProfileAddressCollectionTransfer $merchantProfileAddressCollectionTransfer
    ): ?MerchantProfileAddressCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyMerchantProfileAddressEntityTransfer[] $spyMerchantProfileAddresses
     * @param \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer $merchantProfileAddressCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer|null
     */
    public function mapMerchantProfileAddressEntityTransfersToMerchantProfileAddressCollectionTransfer(
        array $spyMerchantProfileAddresses,
        MerchantProfileAddressCollectionTransfer $merchantProfileAddressCollectionTransfer
    ): ?MerchantProfileAddressCollectionTransfer;

    /**
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress $spyMerchantProfileAddress
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function mapMerchantProfileAddressEntityToMerchantProfileAddressTransfer(
        SpyMerchantProfileAddress $spyMerchantProfileAddress,
        MerchantProfileAddressTransfer $merchantProfileAddressTransfer
    ): MerchantProfileAddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyMerchantProfileAddressEntityTransfer $spyMerchantProfileAddress
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function mapMerchantProfileAddressEntityTransferToMerchantProfileAddressTransfer(
        SpyMerchantProfileAddressEntityTransfer $spyMerchantProfileAddress,
        MerchantProfileAddressTransfer $merchantProfileAddressTransfer
    ): MerchantProfileAddressTransfer;
}
