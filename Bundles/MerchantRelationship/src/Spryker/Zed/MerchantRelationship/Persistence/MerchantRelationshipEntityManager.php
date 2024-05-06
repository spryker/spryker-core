<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnit;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipPersistenceFactory getFactory()
 */
class MerchantRelationshipEntityManager extends AbstractEntityManager implements MerchantRelationshipEntityManagerInterface
{
    /**
     * Specification:
     * - Finds a merchant relationship by merchant relationship ID.
     * - Deletes the merchant relationship.
     *
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function deleteMerchantRelationshipById(int $idMerchantRelationship): void
    {
        $merchantRelationshipEntity = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->filterByIdMerchantRelationship($idMerchantRelationship)
            ->findOne();

        if ($merchantRelationshipEntity) {
            $assignedCompanyBusinessUnitIds = [];
            foreach ($merchantRelationshipEntity->getSpyMerchantRelationshipToCompanyBusinessUnits() as $merchantRelationshipToCompanyBusinessUnit) {
                $assignedCompanyBusinessUnitIds[] = $merchantRelationshipToCompanyBusinessUnit->getFkCompanyBusinessUnit();
            }

            $this->removeAssignedCompanyBusinessUnits($assignedCompanyBusinessUnitIds, $merchantRelationshipEntity->getPrimaryKey());

            $merchantRelationshipEntity->delete();
        }
    }

    /**
     * Specification:
     * - Creates a merchant relationship.
     * - Finds a merchant relationship by MerchantRelationshipTransfer::idMerchantRelationship in the transfer.
     * - Updates fields in a merchant relationship entity.
     * - Persists the entity to DB.
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function saveMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationshipEntity = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->filterByIdMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationship())
            ->findOneOrCreate();

        $merchantRelationshipEntity = $this->getFactory()
            ->createPropelMerchantRelationshipMapper()
            ->mapMerchantRelationshipTransferToEntity($merchantRelationshipTransfer, $merchantRelationshipEntity);

        $merchantRelationshipEntity->save();

        return $this->getFactory()
            ->createPropelMerchantRelationshipMapper()
            ->mapMerchantRelationshipEntityToMerchantRelationshipTransfer($merchantRelationshipEntity, $merchantRelationshipTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @module CompanyBusinessUnit
     *
     * @param array<int> $assignedCompanyBusinessUnitIds
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function addAssignedCompanyBusinessUnits(array $assignedCompanyBusinessUnitIds, int $idMerchantRelationship): CompanyBusinessUnitCollectionTransfer
    {
        $companyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();
        $merchantRelationshipToCompanyBusinessUnitEntityCollection = new ObjectCollection();
        $merchantRelationshipToCompanyBusinessUnitEntityCollection->setModel(SpyMerchantRelationshipToCompanyBusinessUnit::class);

        $companyBusinessUnitEntityCollection = $this->getFactory()
            ->getCompanyBusinessUnitPropelQuery()
            ->filterByIdCompanyBusinessUnit_In($assignedCompanyBusinessUnitIds)
            ->find();

        foreach ($assignedCompanyBusinessUnitIds as $idAssignedCompanyBusinessUnit) {
            $merchantRelationshipToCompanyBusinessUnitEntity = new SpyMerchantRelationshipToCompanyBusinessUnit();
            $merchantRelationshipToCompanyBusinessUnitEntity
                ->setFkCompanyBusinessUnit($idAssignedCompanyBusinessUnit)
                ->setFkMerchantRelationship($idMerchantRelationship);

            $merchantRelationshipToCompanyBusinessUnitEntityCollection->append($merchantRelationshipToCompanyBusinessUnitEntity);

            $companyBusinessUnitCollectionTransfer = $this->addCompanyBusinessUnitTransferToCompanyBusinessUnitCollectionTransfer(
                $companyBusinessUnitEntityCollection,
                $idAssignedCompanyBusinessUnit,
                $companyBusinessUnitCollectionTransfer,
            );
        }

        $merchantRelationshipToCompanyBusinessUnitEntityCollection->save();

        return $companyBusinessUnitCollectionTransfer;
    }

    /**
     * {@inheritDoc}
     *
     * @param array<int> $assignedCompanyBusinessUnitIds
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function removeAssignedCompanyBusinessUnits(array $assignedCompanyBusinessUnitIds, int $idMerchantRelationship): void
    {
        if (!$assignedCompanyBusinessUnitIds) {
            return;
        }

        $entities = $this->getFactory()
            ->createMerchantRelationshipToCompanyBusinessUnitQuery()
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->filterByFkCompanyBusinessUnit_In($assignedCompanyBusinessUnitIds)
            ->find();

        foreach ($entities as $entity) {
            $entity->delete();
        }
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\CompanyBusinessUnit\Persistence\Base\SpyCompanyBusinessUnit> $companyBusinessUnitEntityCollection
     * @param int $idAssignedCompanyBusinessUnit
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function addCompanyBusinessUnitTransferToCompanyBusinessUnitCollectionTransfer(
        Collection $companyBusinessUnitEntityCollection,
        int $idAssignedCompanyBusinessUnit,
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        foreach ($companyBusinessUnitEntityCollection as $companyBusinessUnitEntity) {
            if ($companyBusinessUnitEntity->getIdCompanyBusinessUnit() === $idAssignedCompanyBusinessUnit) {
                return $companyBusinessUnitCollectionTransfer->addCompanyBusinessUnit(
                    (new CompanyBusinessUnitTransfer())->fromArray($companyBusinessUnitEntity->toArray(), true),
                );
            }
        }

        return $companyBusinessUnitCollectionTransfer;
    }
}
