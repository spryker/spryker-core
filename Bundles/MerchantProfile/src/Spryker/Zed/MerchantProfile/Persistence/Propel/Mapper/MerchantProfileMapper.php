<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantProfileTransfer;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile;

class MerchantProfileMapper implements MerchantProfileMapperInterface
{
    /**
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile $merchantProfileEntity
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function mapMerchantProfileEntityToMerchantProfileTransfer(
        SpyMerchantProfile $merchantProfileEntity,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        $merchantProfileTransfer = new MerchantProfileTransfer();
        $merchantProfileTransfer->fromArray(
            $merchantProfileEntity->toArray(),
            true
        );

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile $merchantProfileEntity
     *
     * @return \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile
     */
    public function mapMerchantProfileTransferToMerchantEntity(
        MerchantProfileTransfer $merchantProfileTransfer,
        SpyMerchantProfile $merchantProfileEntity
    ): SpyMerchantProfile {
        $merchantProfileEntity->fromArray(
            $merchantProfileTransfer->modifiedToArray(false)
        );

        return $merchantProfileEntity;
    }
}
