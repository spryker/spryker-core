<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business;

use Generated\Shared\Transfer\MerchantAddressTransfer;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Merchant\Business\MerchantBusinessFactory getFactory()
 * @method \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface getRepository()
 * @method \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface getEntityManager()
 */
class MerchantFacade extends AbstractFacade implements MerchantFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function createMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantWriter()
            ->create($merchantTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function updateMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantWriter()
            ->update($merchantTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function deleteMerchant(MerchantTransfer $merchantTransfer): void
    {
        $this->getFactory()
            ->createMerchantWriter()
            ->delete($merchantTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchantByIdMerchant(int $idMerchant): ?MerchantTransfer
    {
        return $this->getFactory()
            ->createMerchantReader()
            ->findMerchantByIdMerchant($idMerchant);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchantByEmail(string $email): ?MerchantTransfer
    {
        return $this->getFactory()
            ->createMerchantReader()
            ->findMerchantByEmail($email);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchantCollection(): MerchantCollectionTransfer
    {
        return $this->getRepository()->getMerchantCollection();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function createMerchantAddress(MerchantAddressTransfer $merchantAddressTransfer): MerchantAddressTransfer
    {
        return $this->getFactory()
            ->createMerchantAddressWriter()
            ->create($merchantAddressTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idMerchantAddress
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer|null
     */
    public function findMerchantAddressByIdMerchantAddress(int $idMerchantAddress): ?MerchantAddressTransfer
    {
        return $this->getFactory()
            ->createMerchantAddressReader()
            ->findMerchantAddressByIdMerchantAddress($idMerchantAddress);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $currentStatus
     *
     * @return string[]
     */
    public function getApplicableMerchantStatuses(string $currentStatus): array
    {
        return $this->getFactory()->createMerchantStatusReader()->getApplicableMerchantStatuses($currentStatus);
    }
}
