<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ShipmentGuiUniqueShipmentCarrierNameValidator extends ConstraintValidator
{
    /**
     * @param string $value
     * @param \Spryker\Zed\ShipmentGui\Communication\Form\Constraint\ShipmentGuiUniqueShipmentCarrierName $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if ($this->assertAlreadyExistsCarrierName($value, $constraint) === false) {
            return;
        }

        $this->buildViolation($value, $constraint);
    }

    /**
     * @param string $carrierName
     * @param \Spryker\Zed\ShipmentGui\Communication\Form\Constraint\ShipmentGuiUniqueShipmentCarrierName $constraint
     *
     * @return bool
     */
    protected function assertAlreadyExistsCarrierName(string $carrierName, ShipmentGuiUniqueShipmentCarrierName $constraint): bool
    {
        return $constraint->getShipmentFacade()->hasCarrierName($this->createShipmentCarrierTransfer($carrierName, $constraint));
    }

    /**
     * @param string $carrierName
     * @param \Spryker\Zed\ShipmentGui\Communication\Form\Constraint\ShipmentGuiUniqueShipmentCarrierName $constraint
     *
     * @return void
     */
    protected function buildViolation(string $carrierName, ShipmentGuiUniqueShipmentCarrierName $constraint): void
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ name }}', $carrierName)
            ->addViolation();
    }

    /**
     * @param \Spryker\Zed\ShipmentGui\Communication\Form\Constraint\ShipmentGuiUniqueShipmentCarrierName $constraint
     *
     * @return int[]
     */
    protected function getExcludedIdCarriers(ShipmentGuiUniqueShipmentCarrierName $constraint): array
    {
        $idExcludedCarrierFields = $constraint->getIdExcludedCarrierFields();
        $idExcludedCarriers = [];
        $formData = $this->context->getRoot()->getData();

        foreach ($idExcludedCarrierFields as $fieldName) {
            if (empty($formData[$fieldName])) {
                continue;
            }

            $idExcludedCarriers[] = (int)$formData[$fieldName];
        }

        return $idExcludedCarriers;
    }

    /**
     * @param string $carrierName
     * @param \Spryker\Zed\ShipmentGui\Communication\Form\Constraint\ShipmentGuiUniqueShipmentCarrierName $constraint
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    protected function createShipmentCarrierTransfer(string $carrierName, ShipmentGuiUniqueShipmentCarrierName $constraint): ShipmentCarrierTransfer
    {
        return (new ShipmentCarrierTransfer())
            ->setName($carrierName)
            ->setIdExcludedCarriers($this->getExcludedIdCarriers($constraint));
    }
}
