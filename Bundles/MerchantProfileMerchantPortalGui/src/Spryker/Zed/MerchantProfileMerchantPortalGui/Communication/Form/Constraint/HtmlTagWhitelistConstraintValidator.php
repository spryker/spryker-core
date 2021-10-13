<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Constraint;

use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\MerchantProfileMerchantPortalGuiCommunicationFactory getFactory()
 */
class HtmlTagWhitelistConstraintValidator extends AbstractConstraintValidator
{
    /**
     * Checks if the passed value has only white listed HTML tags.
     *
     * @param string $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value) {
            return;
        }

        if (!$constraint instanceof HtmlTagWhitelistConstraint) {
            throw new UnexpectedTypeException($constraint, HtmlTagWhitelistConstraint::class);
        }

        $htmlTagWhiteList = $constraint->getAllowedHtmlTags();

        if (mb_strlen($value) === mb_strlen(strip_tags($value, implode('', $htmlTagWhiteList)))) {
            return;
        }

        $this->context
            ->buildViolation($this->resolveValidationMessage($htmlTagWhiteList))
            ->addViolation();
    }

    /**
     * @param array<string> $htmlTagWhiteList
     *
     * @return string
     */
    protected function resolveValidationMessage(array $htmlTagWhiteList): string
    {
        if (!$htmlTagWhiteList) {
            return 'Value should not have html tags.';
        }

        return sprintf('Value should have only html from white list: %s.', implode(', ', $htmlTagWhiteList));
    }
}
