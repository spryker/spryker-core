<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Validator;

use Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Discount\Business\Validator\ConstraintProvider\DiscountConfiguratorConstraintProviderInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToTranslatorFacadeInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DiscountConfiguratorPeriodValidator implements DiscountConfiguratorValidatorInterface
{
    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @var \Spryker\Zed\Discount\Business\Validator\ConstraintProvider\DiscountConfiguratorConstraintProviderInterface
     */
    protected $discountConfiguratorConstraintProvider;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param \Spryker\Zed\Discount\Business\Validator\ConstraintProvider\DiscountConfiguratorConstraintProviderInterface $discountConfiguratorConstraintProvider
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        ValidatorInterface $validator,
        DiscountConfiguratorConstraintProviderInterface $discountConfiguratorConstraintProvider,
        DiscountToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->validator = $validator;
        $this->discountConfiguratorConstraintProvider = $discountConfiguratorConstraintProvider;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     * @param \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer $discountConfiguratorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer
     */
    public function validateDiscountConfigurator(
        DiscountConfiguratorTransfer $discountConfiguratorTransfer,
        DiscountConfiguratorResponseTransfer $discountConfiguratorResponseTransfer
    ): DiscountConfiguratorResponseTransfer {
        $discountGeneralTransfer = $discountConfiguratorTransfer->getDiscountGeneralOrFail();

        /** @var \Symfony\Component\Validator\Mapping\ClassMetadata $classMetadata */
        $classMetadata = $this->validator->getMetadataFor(DiscountGeneralTransfer::class);
        foreach ($this->discountConfiguratorConstraintProvider->getConstraints() as $property => $constraints) {
            $classMetadata->addPropertyConstraints($property, $constraints);
        }

        $constraintViolationList = $this->validator->validate($discountGeneralTransfer);
        if ($constraintViolationList->count() === 0) {
            return $discountConfiguratorResponseTransfer;
        }

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            $discountConfiguratorResponseTransfer->addMessage($this->createMessageTransfer($constraintViolation));
        }

        return $discountConfiguratorResponseTransfer->setIsSuccessful(false);
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(
        ConstraintViolationInterface $constraintViolation
    ): MessageTransfer {
        return (new MessageTransfer())
            ->setValue($this->translatorFacade->trans($constraintViolation->getMessageTemplate()))
            ->setParameters($constraintViolation->getParameters());
    }
}
