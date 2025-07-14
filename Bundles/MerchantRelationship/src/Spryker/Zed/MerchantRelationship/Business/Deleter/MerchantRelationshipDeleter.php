<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Deleter;

use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface;

class MerchantRelationshipDeleter implements MerchantRelationshipDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface
     */
    protected $merchantRelationshipEntityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface
     */
    protected MerchantRelationshipReaderInterface $merchantRelationshipReader;

    /**
     * @var list<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPreDeletePluginInterface>
     */
    protected $merchantRelationshipPreDeletePlugins;

    /**
     * @var list<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostDeletePluginInterface>
     */
    protected array $merchantRelationshipPostDeletePlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface $merchantRelationshipEntityManager
     * @param \Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface $merchantRelationshipReader
     * @param list<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPreDeletePluginInterface> $merchantRelationshipPreDeletePlugins
     * @param list<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostDeletePluginInterface> $merchantRelationshipPostDeletePlugins
     */
    public function __construct(
        MerchantRelationshipEntityManagerInterface $merchantRelationshipEntityManager,
        MerchantRelationshipReaderInterface $merchantRelationshipReader,
        array $merchantRelationshipPreDeletePlugins,
        array $merchantRelationshipPostDeletePlugins
    ) {
        $this->merchantRelationshipEntityManager = $merchantRelationshipEntityManager;
        $this->merchantRelationshipReader = $merchantRelationshipReader;
        $this->merchantRelationshipPreDeletePlugins = $merchantRelationshipPreDeletePlugins;
        $this->merchantRelationshipPostDeletePlugins = $merchantRelationshipPostDeletePlugins;
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

        if (!$this->findMerchantRelationship($merchantRelationshipTransfer)) {
            return;
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
        $this->executeMerchantRelationshipPostDeletePlugins($merchantRelationshipTransfer);
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

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    protected function executeMerchantRelationshipPostDeletePlugins(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        foreach ($this->merchantRelationshipPostDeletePlugins as $merchantRelationshipPostDeletePlugin) {
            $merchantRelationshipPostDeletePlugin->execute($merchantRelationshipTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    protected function findMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): ?MerchantRelationshipTransfer
    {
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->addIdMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationshipOrFail());
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);

        /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
        $merchantRelationshipCollectionTransfer = $this->merchantRelationshipReader->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        return $merchantRelationshipCollectionTransfer->getMerchantRelationships()
            ->getIterator()
            ->current();
    }
}
