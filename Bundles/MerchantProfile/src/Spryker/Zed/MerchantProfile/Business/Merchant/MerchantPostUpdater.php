<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\Merchant;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileWriterInterface;

class MerchantPostUpdater implements MerchantPostUpdaterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileWriterInterface
     */
    protected $merchantProfileWriter;

    /**
     * @var \Spryker\Zed\MerchantProfile\Business\Merchant\MerchantPostCreatorInterface
     */
    protected $merchantPostCreator;

    /**
     * @param \Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileWriterInterface $merchantProfileWriter
     * @param \Spryker\Zed\MerchantProfile\Business\Merchant\MerchantPostCreatorInterface $merchantPostCreator
     */
    public function __construct(
        MerchantProfileWriterInterface $merchantProfileWriter,
        MerchantPostCreatorInterface $merchantPostCreator
    ) {
        $this->merchantProfileWriter = $merchantProfileWriter;
        $this->merchantPostCreator = $merchantPostCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $originalMerchantTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $updatedMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function handleMerchantPostUpdate(MerchantTransfer $originalMerchantTransfer, MerchantTransfer $updatedMerchantTransfer): MerchantResponseTransfer
    {
        $merchantProfileTransfer = $updatedMerchantTransfer->getMerchantProfile();

        if (!$merchantProfileTransfer || $merchantProfileTransfer->getIdMerchantProfile() === null) {
            return $this->merchantPostCreator->handleMerchantPostCreate($updatedMerchantTransfer);
        }
        $merchantProfileTransfer->setFkMerchant($updatedMerchantTransfer->getIdMerchant());

        $merchantProfileTransfer = $this->merchantProfileWriter->update($merchantProfileTransfer);

        return (new MerchantResponseTransfer())
            ->setIsSuccess(true)
            ->setMerchant($updatedMerchantTransfer->setMerchantProfile($merchantProfileTransfer));
    }
}
