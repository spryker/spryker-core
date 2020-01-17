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
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostCreatePluginInterface;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 */
class MerchantPortalAdminMerchantPostSavePlugin extends AbstractPlugin implements MerchantPostUpdatePluginInterface, MerchantPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates user from merchant data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $originalMerchantTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $updatedMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function postUpdate(MerchantTransfer $originalMerchantTransfer, MerchantTransfer $updatedMerchantTransfer): MerchantResponseTransfer
    {
        $merchantUserTransfer = $this->getFacade()->findOne((new MerchantUserCriteriaFilterTransfer())->setIdMerchant($updatedMerchantTransfer->getIdMerchant()));
        if ($merchantUserTransfer) {
            $merchantUserResponseTransfer = $this->getFacade()->updateByMerchant($merchantUserTransfer, $updatedMerchantTransfer);

            return $this->createMerchantResponseTransfer($merchantUserResponseTransfer, $updatedMerchantTransfer);
        }

        $merchantUserResponseTransfer = $this->getFacade()->createByMerchant($updatedMerchantTransfer);

        return $this->createMerchantResponseTransfer($merchantUserResponseTransfer, $updatedMerchantTransfer);
    }

    /**
     * {@inheritDoc}
     * - Creates or finds a user by provided merchant email.
     * - Creates merchant user relation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function postCreate(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
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
