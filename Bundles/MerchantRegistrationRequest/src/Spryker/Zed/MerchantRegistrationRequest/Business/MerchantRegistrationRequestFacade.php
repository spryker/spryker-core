<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\Business\MerchantRegistrationRequestBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestRepositoryInterface getRepository()
 */
class MerchantRegistrationRequestFacade extends AbstractFacade implements MerchantRegistrationRequestFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function createMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer {
        return $this->getFactory()
            ->createMerchantRegistrationRequestCreator()
            ->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function findMerchantRegistrationRequestById(int $idMerchantRegistrationRequest): ?MerchantRegistrationRequestTransfer
    {
        return $this->getRepository()
            ->findMerchantRegistrationRequestById($idMerchantRegistrationRequest);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function acceptMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer {
        return $this->getFactory()
            ->createMerchantRegistrationRequestAcceptor()
            ->acceptMerchantRegistrationRequest($merchantRegistrationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function rejectMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer {
        return $this->getFactory()
            ->createMerchantRegistrationRequestRejector()
            ->rejectMerchantRegistrationRequest($merchantRegistrationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function expandMerchantRegistrationRequestWithCommentThread(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationRequestTransfer {
        return $this->getFactory()
            ->createMerchantRegistrationRequestExpander()
            ->expandMerchantRegistrationRequestWithCommentThread($merchantRegistrationRequestTransfer);
    }
}
