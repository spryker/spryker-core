<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ShipmentGuiUniqueShipmentCarrierNameValidator extends ConstraintValidator
{
    protected const FIELD_ID_CARRIER = 'id_carrier';

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
        return $constraint->getShipmentFacade()->hasCarrierName($carrierName, $this->getExcludedIdCarriers());
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
     * @return int[]
     */
    protected function getExcludedIdCarriers(): array
    {
        $formData = $this->context->getRoot()->getData();
        $excludedIdCarriers = [];
        $idCarrier = $formData[static::FIELD_ID_CARRIER] ?? null;
        if ($idCarrier !== null) {
            $excludedIdCarriers[] = (int)$idCarrier;
        }

        return $excludedIdCarriers;
    }
}
