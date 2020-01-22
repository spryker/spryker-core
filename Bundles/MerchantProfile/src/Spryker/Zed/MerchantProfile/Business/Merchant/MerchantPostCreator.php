<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\Merchant;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileWriterInterface;

class MerchantPostCreator implements MerchantPostCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileWriterInterface
     */
    protected $merchantProfileWriter;

    /**
     * @param \Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileWriterInterface $merchantProfileWriter
     */
    public function __construct(MerchantProfileWriterInterface $merchantProfileWriter)
    {
        $this->merchantProfileWriter = $merchantProfileWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function handleMerchantPostCreate(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $merchantProfileTransfer = $merchantTransfer->getMerchantProfile()->setFkMerchant($merchantTransfer->getIdMerchant());
        $merchantProfileTransfer = $this->merchantProfileWriter->create($merchantProfileTransfer);
        $merchantTransfer->setMerchantProfile($merchantProfileTransfer);

        return (new MerchantResponseTransfer())
            ->setIsSuccess(true)
            ->setMerchant($merchantTransfer);
    }
}
