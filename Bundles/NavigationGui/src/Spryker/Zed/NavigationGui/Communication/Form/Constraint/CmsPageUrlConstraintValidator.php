<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use InvalidArgumentException;
use Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeLocalizedAttributesFormType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CmsPageUrlConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CmsPageUrlConstraint) {
            throw new UnexpectedTypeException($constraint, CmsPageUrlConstraint::class);
        }

        if (!$value instanceof NavigationNodeLocalizedAttributesTransfer) {
            throw new InvalidArgumentException(sprintf(
                'Expected instance of %s, got %s',
                NavigationNodeLocalizedAttributesTransfer::class,
                $value
            ));
        }

        if (!$value->getCmsPageUrl()) {
            return;
        }

        $urlTransfer = $constraint->findUrl($value);

        if (!$urlTransfer || !$urlTransfer->getFkResourcePage() || $urlTransfer->getFkLocale() != $value->getFkLocale()) {
            $this->context
                ->buildViolation('This value is not a valid CMS page URL for the given locale.')
                ->atPath(NavigationNodeLocalizedAttributesFormType::FIELD_CMS_PAGE_URL)
                ->addViolation();
        }
    }
}
