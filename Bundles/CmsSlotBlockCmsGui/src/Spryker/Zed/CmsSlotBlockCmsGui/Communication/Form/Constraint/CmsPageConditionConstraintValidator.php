<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\Constraint;

use InvalidArgumentException;
use Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\CmsPageSlotBlockConditionForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CmsPageConditionConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\Constraint\CmsPageConditionConstraint $constraint
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CmsPageConditionConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                CmsPageConditionConstraint::class,
                get_class($constraint)
            ));
        }

        if ($value[CmsPageSlotBlockConditionForm::FIELD_ALL]) {
            return;
        }

        if (!$value[CmsPageSlotBlockConditionForm::FIELD_CMS_PAGE_IDS]) {
            $this->context->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }
}
