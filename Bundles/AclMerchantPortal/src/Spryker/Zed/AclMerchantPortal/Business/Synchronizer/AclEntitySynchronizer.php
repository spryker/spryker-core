<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Synchronizer;

use Spryker\Zed\AclMerchantPortal\Business\Reader\MerchantReaderInterface;
use Spryker\Zed\AclMerchantPortal\Business\Reader\MerchantUserReaderInterface;
use Spryker\Zed\AclMerchantPortal\Business\Saver\AclEntitySaverInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class AclEntitySynchronizer implements AclEntitySynchronizerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Reader\MerchantReaderInterface
     */
    protected MerchantReaderInterface $merchantReader;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Reader\MerchantUserReaderInterface
     */
    protected MerchantUserReaderInterface $merchantUserReader;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Saver\AclEntitySaverInterface
     */
    protected AclEntitySaverInterface $aclEntitySaver;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\Business\Reader\MerchantReaderInterface $merchantReader
     * @param \Spryker\Zed\AclMerchantPortal\Business\Reader\MerchantUserReaderInterface $merchantUserReader
     * @param \Spryker\Zed\AclMerchantPortal\Business\Saver\AclEntitySaverInterface $aclEntitySaver
     */
    public function __construct(
        MerchantReaderInterface $merchantReader,
        MerchantUserReaderInterface $merchantUserReader,
        AclEntitySaverInterface $aclEntitySaver
    ) {
        $this->merchantReader = $merchantReader;
        $this->merchantUserReader = $merchantUserReader;
        $this->aclEntitySaver = $aclEntitySaver;
    }

    /**
     * @return void
     */
    public function synchronizeAclEntitiesForMerchantsAndMerchantUsers(): void
    {
        $this->getTransactionHandler()->handleTransaction(function () {
            $this->synchronizeAclEntitiesForMerchants();
            $this->synchronizeAclEntitiesForMerchantUsers();
        });
    }

    /**
     * @return void
     */
    public function synchronizeAclEntitiesForMerchants(): void
    {
        $merchantTransfersGenerator = $this->merchantReader->getMerchantTransfersGenerator();
        foreach ($merchantTransfersGenerator as $merchantTransfers) {
            $this->saveAclEntitiesForMerchants($merchantTransfers);
        }
    }

    /**
     * @return void
     */
    public function synchronizeAclEntitiesForMerchantUsers(): void
    {
        $merchantUserTransfersGenerator = $this->merchantUserReader->getMerchantUserTransfersGenerator();
        foreach ($merchantUserTransfersGenerator as $merchantUserTransfers) {
            $this->saveAclEntitiesForMerchantUsers($merchantUserTransfers);
        }
    }

    /**
     * @param list<\Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     *
     * @return void
     */
    protected function saveAclEntitiesForMerchants(array $merchantTransfers): void
    {
        foreach ($merchantTransfers as $merchantTransfer) {
            $this->aclEntitySaver->saveAclEntitiesForMerchant($merchantTransfer);
        }
    }

    /**
     * @param list<\Generated\Shared\Transfer\MerchantUserTransfer> $merchantUserTransfers
     *
     * @return void
     */
    protected function saveAclEntitiesForMerchantUsers(array $merchantUserTransfers): void
    {
        foreach ($merchantUserTransfers as $merchantUserTransfer) {
            $this->aclEntitySaver->saveAclEntitiesForMerchantUser($merchantUserTransfer);
        }
    }
}
