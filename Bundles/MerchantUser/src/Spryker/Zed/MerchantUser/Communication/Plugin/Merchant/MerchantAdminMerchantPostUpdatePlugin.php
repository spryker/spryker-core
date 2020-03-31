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
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 */
class MerchantAdminMerchantPostUpdatePlugin extends AbstractPlugin implements MerchantPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates user from merchant data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function postUpdate(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $merchantUserResponseTransfer = $this->getFacade()->updateMerchantAdmin($merchantTransfer);

        return (new MerchantResponseTransfer())
            ->setIsSuccess($merchantUserResponseTransfer->getIsSuccessful())
            ->setErrors($this->convertMessageTransfersToMerchantErrorTransfers($merchantUserResponseTransfer->getErrors()))
            ->setMerchant($merchantTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $messageTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MerchantErrorTransfer[]
     */
    protected function convertMessageTransfersToMerchantErrorTransfers(ArrayObject $messageTransfers): ArrayObject
    {
        $result = new ArrayObject();
        foreach ($messageTransfers as $messageTransfer) {
            $result[] = (new MerchantErrorTransfer())->setMessage($messageTransfer->getMessage());
        }

        return $result;
    }
}
