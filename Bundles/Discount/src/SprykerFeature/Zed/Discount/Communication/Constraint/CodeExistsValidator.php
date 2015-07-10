<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class CodeExistsValidator
 */
class CodeExistsValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint|CodeExists $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        $voucherId = $constraint->getVoucherId();
        $discountQueryContainer = $constraint->getQueryContainer();
        $voucherEntity = $discountQueryContainer->queryVoucher($value)->findOne();

        if (!is_null($voucherEntity)) {
            if (is_null($voucherId)) {
                $this->addValidation($value, $constraint);
            }
            if ($voucherId !== $voucherEntity->getIdDiscountVoucher()) {
                $this->addValidation($value, $constraint);
            }
        }
    }

    /**
     * @param string $value
     * @param Constraint|CodeExists $constraint
     */
    protected function addValidation($value, Constraint $constraint)
    {
        $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }

}
