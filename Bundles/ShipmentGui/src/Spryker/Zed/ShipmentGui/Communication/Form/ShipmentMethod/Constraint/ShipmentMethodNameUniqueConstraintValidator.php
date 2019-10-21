<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ShipmentMethodNameUniqueConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ShipmentMethodNameUniqueConstraint) {
            throw new UnexpectedTypeException($constraint, ShipmentMethodNameUniqueConstraint::CLASS_CONSTRAINT);
        }

        $shipmentMethodTransfer = $constraint->getShipmentFacade()->findShipmentMethodByName($value);
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
