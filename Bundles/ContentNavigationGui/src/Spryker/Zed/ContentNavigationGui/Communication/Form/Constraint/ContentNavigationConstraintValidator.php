<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;
use Generated\Shared\Transfer\ContentParameterMessageTransfer;
use InvalidArgumentException;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\ContentNavigationGui\Communication\ContentNavigationGuiCommunicationFactory getFactory()
 */
class ContentNavigationConstraintValidator extends AbstractConstraintValidator
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

        $navigationData = $constraint->getUtilEncodingService()->decodeJson($value, true);
        $contentNavigationTermTransfer = $this->mapNavigationDataToTransfer($navigationData);

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
     * @param array|null $navigationData
     *
     * @return \Generated\Shared\Transfer\ContentNavigationTermTransfer
     */
    protected function mapNavigationDataToTransfer(?array $navigationData): ContentNavigationTermTransfer
    {
        return $this->getFactory()
            ->createContentNavigationTermDataMapper()
            ->mapNavigationDataToContentNavigationTermTransfer($navigationData);
    }
}
