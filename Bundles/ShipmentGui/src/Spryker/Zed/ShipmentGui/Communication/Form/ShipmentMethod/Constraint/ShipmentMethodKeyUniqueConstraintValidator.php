<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ShipmentMethodKeyUniqueConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ShipmentMethodKeyUniqueConstraint) {
            throw new UnexpectedTypeException($constraint, ShipmentMethodKeyUniqueConstraint::CLASS_CONSTRAINT);
        }

        $shipmentMethodTransfer = $constraint->getShipmentFacade()->findShipmentMethodByKey($value);
        if ($shipmentMethodTransfer === null) {
            return;
        }

        if ($shipmentMethodTransfer->getIdShipmentMethod() === (int)$this->context->getRoot()->getViewData()->getIdShipmentMethod()) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage())
            ->addViolation();
    }
}
