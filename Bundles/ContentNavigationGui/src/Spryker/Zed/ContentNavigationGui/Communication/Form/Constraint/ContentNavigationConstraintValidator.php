<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;
use Generated\Shared\Transfer\ContentParameterMessageTransfer;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContentNavigationConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ContentNavigationConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                ContentNavigationConstraint::class,
                get_class($constraint)
            ));
        }

        $contentNavigationTermTransfer = $this->mapNavigationDataToTransfer($value, $constraint);

        $contentValidationResponseTransfer = $constraint
            ->getContentNavigationFacade()
            ->validateContentNavigationTerm($contentNavigationTermTransfer);

        if (!$contentValidationResponseTransfer->getIsSuccess()) {
            foreach ($contentValidationResponseTransfer->getParameterMessages() as $parameterMessage) {
                $this->addViolations($parameterMessage);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ContentParameterMessageTransfer $parameterMessageTransfer
     *
     * @return void
     */
    protected function addViolations(ContentParameterMessageTransfer $parameterMessageTransfer): void
    {
        foreach ($parameterMessageTransfer->getMessages() as $messageTransfer) {
            $text = strtr($messageTransfer->getValue(), $messageTransfer->getParameters());
            $this->context
                ->buildViolation($text)
                ->atPath(sprintf('[%s]', $parameterMessageTransfer->getParameter()))
                ->addViolation();
        }
    }

    /**
     * @param string|null $navigationData
     * @param \Spryker\Zed\ContentNavigationGui\Communication\Form\Constraint\ContentNavigationConstraint $constraint
     *
     * @return \Generated\Shared\Transfer\ContentNavigationTermTransfer
     */
    protected function mapNavigationDataToTransfer(?string $navigationData, ContentNavigationConstraint $constraint): ContentNavigationTermTransfer
    {
        $contentNavigationTermTransfer = new ContentNavigationTermTransfer();

        if ($navigationData === null) {
            return $contentNavigationTermTransfer;
        }

        $navigationData = $constraint->getUtilEncodingService()->decodeJson($navigationData, true);
        $contentNavigationTermTransfer->fromArray($navigationData);

        return $contentNavigationTermTransfer;
    }
}
