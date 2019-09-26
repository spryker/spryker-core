<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantProfileTransfer;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile;

interface MerchantProfileMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile $spyMerchantProfile
     *
     * @return \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile
     */
    public function mapMerchantProfileTransferToMerchantProfileEntity(
        MerchantProfileTransfer $merchantProfileTransfer,
        SpyMerchantProfile $spyMerchantProfile
    ): SpyMerchantProfile;

    /**
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile $spyMerchantProfile
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function mapMerchantProfileEntityToMerchantProfileTransfer(
        SpyMerchantProfile $spyMerchantProfile,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer;
}
