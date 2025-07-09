<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Validator;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitConditionsTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCriteriaTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface;

class ProductMeasurementUnitValidator implements ProductMeasurementUnitValidatorInterface
{
 /**
  * @var string
  */
    protected const MESSAGE_CODE_ALREADY_EXISTS = 'measurement_unit.code.already_exists';

    /**
     * @var string
     */
    protected const MESSAGE_CODE_ASSIGNED = 'measurement_unit.code.in_use';

    /**
     * @var string
     */
    protected const MESSAGE_PRECISION_INVALID = 'measurement_unit.precision.invalid';

    /**
     * @var string
     */
    protected const MESSAGE_CODE_NOT_FOUND = 'measurement_unit.code.not_found';

    /**
     * @var string
     */
    protected const MESSAGE_NO_CODE_PROVIDED = 'measurement_unit.codes.empty';

    /**
     * @var string
     */
    protected const MESSAGE_PARAM_CODE = 'code';

    /**
     * @var string
     */
    protected const MESSAGE_PARAM_DEFAULT_PRECISION = 'default_precision';

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     */
    public function __construct(protected ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $requestTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer $responseTransfer
     * @param array $invalidCodes
     *
     * @return void
     */
    public function validatePrecision(
        ProductMeasurementUnitCollectionRequestTransfer $requestTransfer,
        ProductMeasurementUnitCollectionResponseTransfer $responseTransfer,
        array &$invalidCodes
    ): void {
        foreach ($requestTransfer->getProductMeasurementUnits() as $unitTransfer) {
            if (!is_int($unitTransfer->getDefaultPrecision()) || $unitTransfer->getDefaultPrecision() <= 0) {
                $responseTransfer->addError(
                    $this->createMeasurementUnitInvalidPrecisionErrorMessage(
                        $unitTransfer->getCode(),
                        (int)$unitTransfer->getDefaultPrecision(),
                    ),
                );
                $invalidCodes[] = $unitTransfer->getCode();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer $productMeasurementUnitCollectionResponseTransfer
     * @param array $invalidCodes
     *
     * @return void
     */
    public function validateProductMeasurementUnitsExist(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer,
        ProductMeasurementUnitCollectionResponseTransfer $productMeasurementUnitCollectionResponseTransfer,
        array &$invalidCodes
    ): void {
        /** @var array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer> $productMeasurementUnitTransfers */
        $productMeasurementUnitTransfers = $productMeasurementUnitCollectionRequestTransfer->getProductMeasurementUnits()->getArrayCopy();

        /** @var array<string> $inputCodes */
        $inputCodes = array_map(fn (ProductMeasurementUnitTransfer $transfer) => $transfer->getCode(), $productMeasurementUnitTransfers);

        $existingCodes = $this->productMeasurementUnitRepository->getProductMeasurementUnitCodesByCodes($inputCodes);
        $missingCodes = array_diff($inputCodes, $existingCodes);
        foreach ($missingCodes as $missingCode) {
            $invalidCodes[] = $missingCode;
            $productMeasurementUnitCollectionResponseTransfer->addError($this->createMeasurementUnitNotFoundErrorMessage($missingCode));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer $productMeasurementUnitCollectionResponseTransfer
     * @param array $invalidCodes
     *
     * @return void
     */
    public function validateProductMeasurementUnitsNotExist(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer,
        ProductMeasurementUnitCollectionResponseTransfer $productMeasurementUnitCollectionResponseTransfer,
        array &$invalidCodes
    ): void {
        /** @var array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer> $productMeasurementUnitTransfers */
        $productMeasurementUnitTransfers = $productMeasurementUnitCollectionRequestTransfer->getProductMeasurementUnits()->getArrayCopy();

        /** @var array<string> $inputCodes */
        $inputCodes = array_map(fn (ProductMeasurementUnitTransfer $transfer) => $transfer->getCode(), $productMeasurementUnitTransfers);

        $existingCodes = $this->productMeasurementUnitRepository->getProductMeasurementUnitCodesByCodes($inputCodes);
        foreach ($existingCodes as $existingCode) {
            $invalidCodes[] = $existingCode;
            $productMeasurementUnitCollectionResponseTransfer->addError($this->createMeasurementUnitCodeAlreadyExistsErrorMessage($existingCode));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionDeleteCriteriaTransfer $productMeasurementUnitCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    public function validateDeleteCriteria(
        ProductMeasurementUnitCollectionDeleteCriteriaTransfer $productMeasurementUnitCollectionDeleteCriteriaTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer {
        $productMeasurementUnitCollectionResponseTransfer = new ProductMeasurementUnitCollectionResponseTransfer();

        $inputCodes = $productMeasurementUnitCollectionDeleteCriteriaTransfer->getCodes();
        if (count($inputCodes) < 1) {
            $productMeasurementUnitCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(static::MESSAGE_NO_CODE_PROVIDED),
            );

            return $productMeasurementUnitCollectionResponseTransfer;
        }

        $productMeasurementUnitConditionsTransfer = (new ProductMeasurementUnitConditionsTransfer())
            ->setCodes($inputCodes);
        $productMeasurementUnitCriteriaTransfer = (new ProductMeasurementUnitCriteriaTransfer())
            ->setProductMeasurementUnitConditions($productMeasurementUnitConditionsTransfer);

        /** @var array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer> $existingProductMeasurementUnitTransfers */
        $existingProductMeasurementUnitTransfers = $this->productMeasurementUnitRepository
            ->getProductMeasurementUnitCollection($productMeasurementUnitCriteriaTransfer)
            ->getProductMeasurementUnits()
            ->getArrayCopy();

        /** @var array<string> $existingCodes */
        $existingCodes = array_map(
            static fn (ProductMeasurementUnitTransfer $productMeasurementUnitTransfer) => $productMeasurementUnitTransfer->getCode(),
            $existingProductMeasurementUnitTransfers,
        );

        if (count($inputCodes) !== count($existingCodes)) {
            $missingCodes = array_diff($inputCodes, $existingCodes);

            foreach ($missingCodes as $missingCode) {
                $productMeasurementUnitCollectionResponseTransfer->addError($this->createMeasurementUnitCodeNotFoundErrorMessage($missingCode));
            }
        }

        foreach ($existingProductMeasurementUnitTransfers as $productMeasurementUnitTransfer) {
            $productAssignmentCount = $this->productMeasurementUnitRepository->countProductAssignments($productMeasurementUnitTransfer->getIdProductMeasurementUnit());

            if ($productAssignmentCount > 0) {
                $productMeasurementUnitCollectionResponseTransfer->addError(
                    $this->createMeasurementUnitInUseErrorMessage($productMeasurementUnitTransfer->getCode()),
                );

                continue;
            }

            $productMeasurementUnitCollectionResponseTransfer->addProductMeasurementUnit($productMeasurementUnitTransfer);
        }

        return $productMeasurementUnitCollectionResponseTransfer;
    }

    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createMeasurementUnitCodeNotFoundErrorMessage(string $code): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setMessage(static::MESSAGE_CODE_NOT_FOUND)
            ->setParameters([static::MESSAGE_PARAM_CODE => $code]);
    }

    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createMeasurementUnitInUseErrorMessage(string $code): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setMessage(static::MESSAGE_CODE_ASSIGNED)
            ->setParameters([static::MESSAGE_PARAM_CODE => $code]);
    }

    /**
     * @param string $code
     * @param int $defaultPrecision
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createMeasurementUnitInvalidPrecisionErrorMessage(string $code, int|null $defaultPrecision): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setMessage(static::MESSAGE_PRECISION_INVALID)
            ->setParameters([static::MESSAGE_PARAM_CODE => $code, static::MESSAGE_PARAM_DEFAULT_PRECISION => $defaultPrecision]);
    }

    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createMeasurementUnitNotFoundErrorMessage(string $code): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setMessage(static::MESSAGE_CODE_NOT_FOUND)
            ->setParameters([static::MESSAGE_PARAM_CODE => $code]);
    }

    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createMeasurementUnitCodeAlreadyExistsErrorMessage(string $code): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setMessage(static::MESSAGE_CODE_ALREADY_EXISTS)
            ->setParameters([static::MESSAGE_PARAM_CODE => $code]);
    }
}
