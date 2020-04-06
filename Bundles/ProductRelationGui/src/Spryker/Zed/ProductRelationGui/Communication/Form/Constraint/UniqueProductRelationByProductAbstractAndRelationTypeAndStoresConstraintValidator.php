<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductRelationCriteriaTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueProductRelationByProductAbstractAndRelationTypeAndStoresConstraintValidator extends ConstraintValidator
{
    /**
     * @uses \Orm\Zed\Store\Persistence\Map\SpyStoreTableMap::COL_ID_STORE
     */
    protected const COL_ID_STORE = 'spy_store.id_store';

    /**
     * @uses \Orm\Zed\Store\Persistence\Map\SpyStoreTableMap::COL_NAME
     */
    protected const COL_STORE_NAME = 'spy_store.name';

    /**
     * Checks if the passed productRelationTransfer is valid.
     *
     * @param mixed|\Generated\Shared\Transfer\ProductRelationTransfer $value The productRelationTransfer that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     *
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }

        if (!$constraint instanceof UniqueProductRelationByProductAbstractAndRelationTypeAndStoresConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueProductRelationByProductAbstractAndRelationTypeAndStoresConstraint::class);
        }

        $this->checkDuplicatedStores($constraint, $value);
    }

    /**
     * @param \Spryker\Zed\ProductRelationGui\Communication\Form\Constraint\UniqueProductRelationByProductAbstractAndRelationTypeAndStoresConstraint $uniqueProductRelationByProductAbstractAndRelationTypeAndStoresConstraint
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return void
     */
    protected function checkDuplicatedStores(
        UniqueProductRelationByProductAbstractAndRelationTypeAndStoresConstraint $uniqueProductRelationByProductAbstractAndRelationTypeAndStoresConstraint,
        ProductRelationTransfer $productRelationTransfer
    ): void {
        $productRelationCriteriaTransfer = (new ProductRelationCriteriaTransfer())
            ->setProductRelationKey($productRelationTransfer->getProductRelationKey())
            ->setFkProductAbstract($productRelationTransfer->getFkProductAbstract())
            ->setRelationTypeKey($productRelationTransfer->getProductRelationType()->getKey());

        $storeData = $uniqueProductRelationByProductAbstractAndRelationTypeAndStoresConstraint->getProductRelationFacade()
            ->getStoresByProductRelationCriteria($productRelationCriteriaTransfer);

        $groupedStoreData = $this->groupStoresById($storeData);
        $idStoresFromPersistence = array_keys($groupedStoreData);
        $currentIdStores = $productRelationTransfer->getStoreRelation()->getIdStores();

        if (array_intersect($idStoresFromPersistence, $currentIdStores) !== []) {
            $this->createViolationMessage(array_values($groupedStoreData));

            return;
        }
    }

    /**
     * @param array $storeData
     *
     * @return string[]
     */
    protected function groupStoresById(array $storeData): array
    {
        $groupedStoreData = [];

        foreach ($storeData as $store) {
            $groupedStoreData[$store[static::COL_ID_STORE]] = $store[static::COL_STORE_NAME];
        }

        return $groupedStoreData;
    }

    /**
     * @param string[] $storeData
     *
     * @return void
     */
    protected function createViolationMessage(array $storeData)
    {
        $this->context
            ->buildViolation(
                sprintf(
                    'Other product relations of this type are already assigned to this stores: %s',
                    implode(',', $storeData)
                )
            )
            ->addViolation();
    }
}
