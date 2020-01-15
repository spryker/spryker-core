<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Communication\Plugin\Merchant;

use ArrayObject;
use Generated\Shared\Transfer\MerchantErrorTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 */
class MerchantUserMerchantPostCreatePlugin extends AbstractPlugin implements MerchantPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates a user by provided merchant email.
     * - Creates merchant user relation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function execute(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $merchantUserResponseTransfer = $this->getFacade()->createMerchantUserByMerchant($merchantTransfer);

        return (new MerchantResponseTransfer())
            ->setIsSuccess($merchantUserResponseTransfer->getIsSuccess())
            ->setErrors($this->convertMessagesToMerchantErrors($merchantUserResponseTransfer->getErrors()))
            ->setMerchant($merchantTransfer);
    }

    /**
     * @param \ArrayObject $messageTransfers
     *
     * @return \ArrayObject
     */
    protected function convertMessagesToMerchantErrors(ArrayObject $messageTransfers): ArrayObject
    {
        $result = new ArrayObject();
        /** @var \Generated\Shared\Transfer\MessageTransfer $messageTransfer */
        foreach ($messageTransfers as $messageTransfer) {
            $result[] = (new MerchantErrorTransfer())->setMessage($messageTransfer->getMessage());
        }

        return $result;
    }
}
