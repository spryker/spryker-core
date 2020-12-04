<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication\Form\Constraints;

use Generated\Shared\Transfer\ContentBannerTermTransfer;
use Generated\Shared\Transfer\ContentParameterMessageTransfer;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContentBannerConstraintValidator extends ConstraintValidator
{
    /**
     * @param string|null $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\ContentBannerGui\Communication\Form\Constraints\ContentBannerConstraint $constraint The constraint for the validation
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ContentBannerConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                ContentBannerConstraint::class,
                get_class($constraint)
            ));
        }

        $contentBannerTermTransfer = $this->mapBannerDataToTransfer($value, $constraint);

        $contentValidationResponseTransfer = $constraint
            ->getContentBannerFacade()
            ->validateContentBannerTerm($contentBannerTermTransfer);

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
            $text = strtr((string)$messageTransfer->getValue(), $messageTransfer->getParameters());
            $this->context
                ->buildViolation($text)
                ->atPath(sprintf('[%s]', $parameterMessageTransfer->getParameter()))
                ->addViolation();
        }
    }

    /**
     * @param string|null $bannerData
     * @param \Spryker\Zed\ContentBannerGui\Communication\Form\Constraints\ContentBannerConstraint $constraint
     *
     * @return \Generated\Shared\Transfer\ContentBannerTermTransfer
     */
    protected function mapBannerDataToTransfer($bannerData, ContentBannerConstraint $constraint): ContentBannerTermTransfer
    {
        $contentBannerTermTransfer = new ContentBannerTermTransfer();

        if ($bannerData !== null) {
            $bannerData = $constraint->getUtilEncoding()->decodeJson($bannerData, true);
            if ($bannerData !== null) {
                $contentBannerTermTransfer->fromArray($bannerData);
            }
        }

        return $contentBannerTermTransfer;
    }
}
