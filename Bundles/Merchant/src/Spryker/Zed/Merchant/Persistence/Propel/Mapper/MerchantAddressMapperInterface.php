<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantAddressTransfer;
use Generated\Shared\Transfer\SpyMerchantAddressEntityTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantAddress;

interface MerchantAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchantAddress $spyMerchantAddress
     *
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantAddress
     */
    public function mapMerchantAddressTransferToSpyMerchantAddressEntity(
        MerchantAddressTransfer $merchantAddressTransfer,
        SpyMerchantAddress $spyMerchantAddress
    ): SpyMerchantAddress;

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchantAddress[] $spyMerchantAddresses
     * @param \Generated\Shared\Transfer\MerchantAddressCollectionTransfer $merchantAddressCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressCollectionTransfer|null
     */
    public function mapMerchantAddressEntitiesToMerchantAddressCollectionTransfer(
        array $spyMerchantAddresses,
        MerchantAddressCollectionTransfer $merchantAddressCollectionTransfer
    ): ?MerchantAddressCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyMerchantAddressEntityTransfer[] $spyMerchantAddresses
     * @param \Generated\Shared\Transfer\MerchantAddressCollectionTransfer $merchantAddressCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressCollectionTransfer|null
     */
    public function mapMerchantAddressEntityTransfersToMerchantAddressCollectionTransfer(
        array $spyMerchantAddresses,
        MerchantAddressCollectionTransfer $merchantAddressCollectionTransfer
    ): ?MerchantAddressCollectionTransfer;

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchantAddress $spyMerchantAddress
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function mapMerchantAddressEntityToMerchantAddressTransfer(
        SpyMerchantAddress $spyMerchantAddress,
        MerchantAddressTransfer $merchantAddressTransfer
    ): MerchantAddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyMerchantAddressEntityTransfer $spyMerchantAddress
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function mapMerchantAddressEntityTransferToMerchantAddressTransfer(
        SpyMerchantAddressEntityTransfer $spyMerchantAddress,
        MerchantAddressTransfer $merchantAddressTransfer
    ): MerchantAddressTransfer;
}
