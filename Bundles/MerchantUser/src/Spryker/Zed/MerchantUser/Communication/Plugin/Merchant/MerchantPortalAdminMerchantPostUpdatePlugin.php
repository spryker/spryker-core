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
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 */
class MerchantPortalAdminMerchantPostUpdatePlugin extends AbstractPlugin implements MerchantPostUpdatePluginInterface
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
    public function execute(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $merchantUserTransfer = $this->getFacade()->findOne((new MerchantUserCriteriaFilterTransfer())->setIdMerchant($merchantTransfer->getIdMerchant()));
        if ($merchantUserTransfer) {
            $merchantUserResponseTransfer = $this->getFacade()->updateUserByMerchant($merchantUserTransfer, $merchantTransfer);

            return $this->createMerchantResponseTransfer($merchantUserResponseTransfer, $merchantTransfer);
        }

        $merchantUserResponseTransfer = $this->getFacade()->createByMerchant($merchantTransfer);

        return $this->createMerchantResponseTransfer($merchantUserResponseTransfer, $merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserResponseTransfer $merchantUserResponseTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    protected function createMerchantResponseTransfer(
        MerchantUserResponseTransfer $merchantUserResponseTransfer,
        MerchantTransfer $merchantTransfer
    ): MerchantResponseTransfer {
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
