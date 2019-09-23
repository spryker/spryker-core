<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Model;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationship\Business\KeyGenerator\MerchantRelationshipKeyGeneratorInterface;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface;

class MerchantRelationshipWriter implements MerchantRelationshipWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\KeyGenerator\MerchantRelationshipKeyGeneratorInterface
     */
    protected $merchantRelationshipKeyGenerator;

    /**
     * @var \Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPreDeletePluginInterface[]
     */
    protected $merchantRelationshipPreDeletePlugins;

    /**
     * @var \Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostCreatePluginInterface[]
     */
    protected $merchantRelationshipPostCreatePlugins;

    /**
     * @var \Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostUpdatePluginInterface[]
     */
    protected $merchantRelationshipPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface $entityManager
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface $repository
     * @param \Spryker\Zed\MerchantRelationship\Business\KeyGenerator\MerchantRelationshipKeyGeneratorInterface $merchantRelationshipKeyGenerator
     * @param \Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPreDeletePluginInterface[] $merchantRelationshipPreDeletePlugins
     * @param \Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostCreatePluginInterface[] $merchantRelationshipPostCreatePlugins
     * @param \Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostUpdatePluginInterface[] $merchantRelationshipPostUpdatePlugins
     */
    public function __construct(
        MerchantRelationshipEntityManagerInterface $entityManager,
        MerchantRelationshipRepositoryInterface $repository,
        MerchantRelationshipKeyGeneratorInterface $merchantRelationshipKeyGenerator,
        array $merchantRelationshipPreDeletePlugins,
        array $merchantRelationshipPostCreatePlugins,
        array $merchantRelationshipPostUpdatePlugins
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->merchantRelationshipKeyGenerator = $merchantRelationshipKeyGenerator;
        $this->merchantRelationshipPreDeletePlugins = $merchantRelationshipPreDeletePlugins;
        $this->merchantRelationshipPostCreatePlugins = $merchantRelationshipPostCreatePlugins;
        $this->merchantRelationshipPostUpdatePlugins = $merchantRelationshipPostUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function create(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($merchantRelationshipTransfer) {
            return $this->executeCreateTransaction($merchantRelationshipTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function executeCreateTransaction(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer
            ->requireFkMerchant()
            ->requireFkCompanyBusinessUnit();

        if (!$merchantRelationshipTransfer->getMerchantRelationshipKey()) {
            $merchantRelationshipTransfer->setMerchantRelationshipKey(
                $this->merchantRelationshipKeyGenerator
                    ->generateMerchantRelationshipKey()
            );
        }

        $merchantRelationshipTransfer = $this->entityManager->saveMerchantRelationship($merchantRelationshipTransfer);
        $this->saveAssignedCompanyBusinessUnits($merchantRelationshipTransfer);
        $merchantRelationshipTransfer = $this->executeMerchantRelationshipPostCreatePlugins($merchantRelationshipTransfer);

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function update(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($merchantRelationshipTransfer) {
            return $this->executeUpdateTransaction($merchantRelationshipTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function executeUpdateTransaction(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer->requireIdMerchantRelationship()
            ->requireFkMerchant()
            ->requireFkCompanyBusinessUnit();

        if (!$merchantRelationshipTransfer->getMerchantRelationshipKey()) {
            $merchantRelationshipTransfer->setMerchantRelationshipKey(
                $this->merchantRelationshipKeyGenerator
                    ->generateMerchantRelationshipKey()
            );
        }

        $merchantRelationshipTransfer = $this->entityManager->saveMerchantRelationship($merchantRelationshipTransfer);
        $this->saveAssignedCompanyBusinessUnits($merchantRelationshipTransfer);
        $this->executeMerchantRelationshipPostUpdatePlugins($merchantRelationshipTransfer);

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function delete(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        $merchantRelationshipTransfer->requireIdMerchantRelationship();

        $this->getTransactionHandler()->handleTransaction(function () use ($merchantRelationshipTransfer) {
            $this->executeDeleteMerchantRelationshipByIdTransaction($merchantRelationshipTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    protected function saveAssignedCompanyBusinessUnits(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        $currentIdAssignedCompanyBusinessUnits = $this->repository
            ->getIdAssignedBusinessUnitsByMerchantRelationshipId($merchantRelationshipTransfer->getIdMerchantRelationship());
        $requestedIdAssignedCompanyBusinessUnits = $this->getIdAssignedCompanyBusinessUnits($merchantRelationshipTransfer);

        $idAssignedCompanyBusinessUnitsToSave = array_diff($requestedIdAssignedCompanyBusinessUnits, $currentIdAssignedCompanyBusinessUnits);
        $idAssignedCompanyBusinessUnitsToDelete = array_diff($currentIdAssignedCompanyBusinessUnits, $requestedIdAssignedCompanyBusinessUnits);

        $this->entityManager->addAssignedCompanyBusinessUnits(
            $idAssignedCompanyBusinessUnitsToSave,
            $merchantRelationshipTransfer->getIdMerchantRelationship()
        );
        $this->entityManager->removeAssignedCompanyBusinessUnits(
            $idAssignedCompanyBusinessUnitsToDelete,
            $merchantRelationshipTransfer->getIdMerchantRelationship()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return int[]
     */
    protected function getIdAssignedCompanyBusinessUnits($merchantRelationshipTransfer): array
    {
        if (!$merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()) {
            return [];
        }

        $companyBusinessUnits = $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits();
        if (!$companyBusinessUnits) {
            return [];
        }

        $ids = [];
        foreach ($companyBusinessUnits as $companyBusinessUnit) {
            $ids[] = $companyBusinessUnit->getIdCompanyBusinessUnit();
        }

        return $ids;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    protected function executeDeleteMerchantRelationshipByIdTransaction(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        $this->executeMerchantRelationshipPreDeletePlugins($merchantRelationshipTransfer);
        $this->entityManager->deleteMerchantRelationshipById($merchantRelationshipTransfer->getIdMerchantRelationship());
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
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function executeMerchantRelationshipPostCreatePlugins(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        foreach ($this->merchantRelationshipPostCreatePlugins as $merchantRelationshipPostCreatePlugin) {
            $merchantRelationshipTransfer = $merchantRelationshipPostCreatePlugin->execute($merchantRelationshipTransfer);
        }

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    protected function executeMerchantRelationshipPostUpdatePlugins(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        foreach ($this->merchantRelationshipPostUpdatePlugins as $merchantRelationshipPostUpdatePlugin) {
            $merchantRelationshipPostUpdatePlugin->execute($merchantRelationshipTransfer);
        }
    }
}
