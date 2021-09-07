<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig;
use Spryker\Zed\PriceProduct\Business\Validator\Constraint\TransferConstraint;
use Spryker\Zed\PriceProductVolume\Business\Validator\Constraint\DefaultPriceTypeConstraint;
use Spryker\Zed\PriceProductVolume\Business\Validator\Constraint\ValidGrossNetPriceConstraint;
use Spryker\Zed\PriceProductVolume\Business\Validator\Constraint\VolumeQuantityConstraint;
use Spryker\Zed\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractorInterface;
use Spryker\Zed\PriceProductVolume\Dependency\External\PriceProductVolumeToValidationAdapterInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PriceProductVolumeValidator implements PriceProductVolumeValidatorInterface
{
    /**
     * @var string
     */
    protected const VALUE_IS_INVALID = 'This value is not valid.';
    /**
     * @var string
     */
    protected const VOLUME_QUANTITY_IS_INVALID = 'This value cannot be blank';

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @var \Spryker\Zed\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractorInterface
     */
    protected $volumePriceExtractor;

    /**
     * @var \Symfony\Component\Validator\Constraint[]
     */
    protected $priceProductCollectionConstraints;

    /**
     * @param \Spryker\Zed\PriceProductVolume\Dependency\External\PriceProductVolumeToValidationAdapterInterface $validationAdapter
     * @param \Spryker\Zed\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractorInterface $volumePriceExtractor
     * @param \Symfony\Component\Validator\Constraint[] $volumePriceCollectionConstraints
     */
    public function __construct(
        PriceProductVolumeToValidationAdapterInterface $validationAdapter,
        VolumePriceExtractorInterface $volumePriceExtractor,
        array $volumePriceCollectionConstraints
    ) {
        $this->validator = $validationAdapter->createValidator();
        $this->volumePriceExtractor = $volumePriceExtractor;
        $this->priceProductCollectionConstraints = $volumePriceCollectionConstraints;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validate(ArrayObject $priceProductTransfers): ValidationResponseTransfer
    {
        $validationResponseTransfer = (new ValidationResponseTransfer())
            ->setIsSuccess(true);

        $constraintViolationList = $this->validator
            ->startContext()
            ->validate($priceProductTransfers, $this->getCollectionConstraints())
            ->getViolations();

        $this->addViolationsToValidationResponse($constraintViolationList, $validationResponseTransfer);

        foreach ($priceProductTransfers as $priceProductIndex => $priceProductTransfer) {
            $volumePriceProductTransfers = $this->volumePriceExtractor
                ->extractPriceProductVolumeTransfersFromArray([$priceProductTransfer]);

            foreach ($volumePriceProductTransfers as $volumePriceProductIndex => $volumePriceProductTransfer) {
                $propertyPath = $this->createPropertyPath($priceProductIndex, $volumePriceProductIndex);
                $constraintViolationList = $this->validator
                    ->startContext()
                    ->atPath($propertyPath)
                    ->validate($volumePriceProductTransfer, $this->createPriceProductVolumeConstraints())
                    ->getViolations();

                $this->addViolationsToValidationResponse($constraintViolationList, $validationResponseTransfer);
            }
        }

        return $validationResponseTransfer;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public function getCollectionConstraints(): array
    {
        return array_merge($this->priceProductCollectionConstraints, $this->createPriceProductConstraints());
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function createPriceProductVolumeConstraints(): array
    {
        return [
            new DefaultPriceTypeConstraint(),
            new ValidGrossNetPriceConstraint(),
            new TransferConstraint(
                [
                    PriceProductTransfer::VOLUME_QUANTITY => new VolumeQuantityConstraint(),
                    PriceProductTransfer::MONEY_VALUE => $this->getMoneyValueTransferConstraint(),
                ]
            ),
        ];
    }

    /**
     * @return array
     */
    protected function createPriceProductConstraints(): array
    {
        return [
            new All([
                new TransferConstraint(
                    [
                        PriceProductTransfer::VOLUME_QUANTITY => $this->getVolumeQuantityConstraint(),
                    ]
                ),
            ]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getMoneyValueTransferConstraint(): SymfonyConstraint
    {
        return new TransferConstraint([
            MoneyValueTransfer::NET_AMOUNT => $this->getNetAmountConstraint(),
            MoneyValueTransfer::GROSS_AMOUNT => $this->getGrossAmountConstraint(),
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getNetAmountConstraint(): SymfonyConstraint
    {
        return new GreaterThanOrEqual([
            'value' => 0,
            'message' => static::VALUE_IS_INVALID,
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getGrossAmountConstraint(): SymfonyConstraint
    {
        return new GreaterThanOrEqual([
            'value' => 0,
            'message' => static::VALUE_IS_INVALID,
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getVolumeQuantityConstraint(): SymfonyConstraint
    {
        return new GreaterThanOrEqual([
            'value' => 1,
            'message' => static::VOLUME_QUANTITY_IS_INVALID,
        ]);
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $constraintViolationList
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    protected function addViolationsToValidationResponse(
        ConstraintViolationListInterface $constraintViolationList,
        ValidationResponseTransfer $validationResponseTransfer
    ): ValidationResponseTransfer {
        if ($constraintViolationList->count() === 0) {
            return $validationResponseTransfer;
        }

        $validationResponseTransfer->setIsSuccess(false);

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            $message = $constraintViolation->getMessage();
            $validationErrorTransfer = (new ValidationErrorTransfer())
                ->setMessage($message)
                ->setPropertyPath($constraintViolation->getPropertyPath())
                ->setInvalidValue($constraintViolation->getInvalidValue())
                ->setRoot($constraintViolation->getRoot());

            $validationResponseTransfer->addValidationError($validationErrorTransfer);
        }

        return $validationResponseTransfer;
    }

    /**
     * @param int $priceProductIndex
     * @param int $volumePriceProductIndex
     *
     * @return string
     */
    protected function createPropertyPath(int $priceProductIndex, int $volumePriceProductIndex): string
    {
        return sprintf(
            '[%d][%s][%s][%s][%d]',
            $priceProductIndex,
            PriceProductTransfer::MONEY_VALUE,
            MoneyValueTransfer::PRICE_DATA,
            PriceProductVolumeConfig::VOLUME_PRICE_TYPE,
            $volumePriceProductIndex
        );
    }
}
