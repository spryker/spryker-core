<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Deleter;

use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface;

class MerchantRelationshipDeleter implements MerchantRelationshipDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface
     */
    protected $merchantRelationshipEntityManager;

    /**
     * @var array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPreDeletePluginInterface>
     */
    protected $merchantRelationshipPreDeletePlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface $merchantRelationshipEntityManager
     * @param array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPreDeletePluginInterface> $merchantRelationshipPreDeletePlugins
     */
    public function __construct(
        MerchantRelationshipEntityManagerInterface $merchantRelationshipEntityManager,
        array $merchantRelationshipPreDeletePlugins
    ) {
        $this->merchantRelationshipEntityManager = $merchantRelationshipEntityManager;
        $this->merchantRelationshipPreDeletePlugins = $merchantRelationshipPreDeletePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer|null $merchantRelationshipRequestTransfer
     *
     * @return void
     */
    public function delete(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ?MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer = null
    ): void {
        if ($merchantRelationshipRequestTransfer === null) {
            trigger_error(
                '[Spryker/MerchantRelationship] Pass the $merchantRelationshipRequestTransfer parameter '
                . 'and use only it in this method for the forward compatibility with next major version.',
                E_USER_DEPRECATED,
            );
        }

        if ($merchantRelationshipRequestTransfer && $merchantRelationshipRequestTransfer->getMerchantRelationshipOrFail()->getIdMerchantRelationship()) {
            $merchantRelationshipTransfer = $merchantRelationshipRequestTransfer->getMerchantRelationshipOrFail();
        }

        $this->deleteByMerchantRelationshipTransfer($merchantRelationshipTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    protected function deleteByMerchantRelationshipTransfer(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($merchantRelationshipTransfer) {
            $this->executeDeleteMerchantRelationshipByIdTransaction($merchantRelationshipTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    protected function executeDeleteMerchantRelationshipByIdTransaction(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        $this->executeMerchantRelationshipPreDeletePlugins($merchantRelationshipTransfer);
        $this->merchantRelationshipEntityManager->deleteMerchantRelationshipById($merchantRelationshipTransfer->getIdMerchantRelationship());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    protected function executeMerchantRelationshipPreDeletePlugins(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        foreach ($this->merchantRelationshipPreDeletePlugins as $merchantRelationshipPreDeletePlugin) {
            $merchantRelationshipPreDeletePlugin->execute($merchantRelationshipTransfer);
        }
    }
}
