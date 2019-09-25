<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfile;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface;

class MerchantProfileWriter implements MerchantProfileWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface
     */
    protected $merchantProfileEntityManager;

    /**
     * @param \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface $merchantProfileEntityManager
     */
    public function __construct(MerchantProfileEntityManagerInterface $merchantProfileEntityManager)
    {
        $this->merchantProfileEntityManager = $merchantProfileEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function saveMerchantProfile(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantProfileTransfer = $merchantTransfer->getMerchantProfile();
        $merchantProfileTransfer->setFkMerchant($merchantTransfer->getIdMerchant());
        $merchantProfileTransfer = $this->merchantProfileEntityManager->saveMerchantProfile($merchantProfileTransfer);
        $merchantTransfer->setMerchantProfile($merchantProfileTransfer);

        return $merchantTransfer;
    }
}
