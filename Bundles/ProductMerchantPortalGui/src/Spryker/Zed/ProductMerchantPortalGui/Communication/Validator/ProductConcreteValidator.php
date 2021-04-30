<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Validator;

use Generated\Shared\Transfer\RowValidationTransfer;
use Generated\Shared\Transfer\TableValidationResponseTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\SkuRegexConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\UniqueAbstractSkuConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapper;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\External\ProductMerchantPortalGuiToValidationAdapterInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationInterface;

class ProductConcreteValidator implements ProductConcreteValidatorInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\External\ProductMerchantPortalGuiToValidationAdapterInterface
     */
    protected $validationAdapter;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\External\ProductMerchantPortalGuiToValidationAdapterInterface $validationAdapter
     */
    public function __construct(ProductMerchantPortalGuiToValidationAdapterInterface $validationAdapter)
    {
        $this->validationAdapter = $validationAdapter;
    }

    /**
     * @param array $concreteProducts
     *
     * @return \Generated\Shared\Transfer\TableValidationResponseTransfer
     */
    public function validateConcreteProducts(array $concreteProducts): TableValidationResponseTransfer
    {
        $constraintViolationList = $this->validationAdapter
            ->createValidator()
            ->validate($concreteProducts, $this->getProductConcreteConstraints());

        $tableValidationResponseTransfer = (new TableValidationResponseTransfer())
            ->setIsSuccess($constraintViolationList->count() === 0 && count($concreteProducts) > 0);

        $rowValidationTransfers = [];

        foreach ($concreteProducts as $index => $concreteProduct) {
            $rowValidationTransfer = new RowValidationTransfer();
            $rowValidationTransfer->setFields([
                ProductConcreteMapper::FIELD_NAME => $concreteProduct[ProductConcreteMapper::FIELD_NAME],
                ProductConcreteMapper::FIELD_SKU => $concreteProduct[ProductConcreteMapper::FIELD_SKU],
            ]);
            $rowValidationTransfers[$index] = $rowValidationTransfer;
        }

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            [$rowNumber, $attributeName] = $this->extractRowNumberAndAttributeName($constraintViolation);

            /** @var \Generated\Shared\Transfer\RowValidationTransfer $rowValidationTransfer */
            $rowValidationTransfer = $rowValidationTransfers[$rowNumber];
            $errors = $rowValidationTransfer->getErrors();
            $errors[$attributeName] = $constraintViolation->getMessage();

            $rowValidationTransfer->setErrors($errors);
        }

        $tableValidationResponseTransfer->setRowValidations($rowValidationTransfers);

        return $tableValidationResponseTransfer;
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation
     *
     * @return array
     */
    protected function extractRowNumberAndAttributeName(ConstraintViolationInterface $constraintViolation): array
    {
        $propertyPath = $constraintViolation->getPropertyPath();
        preg_match_all('/(?<=\[).+?(?=\])/', $propertyPath, $matches);
        $matches = $matches[0];
        $rowNumber = (int)$matches[0];
        $attributeName = $matches[1];

        return [
            $rowNumber,
            $attributeName,
        ];
    }

    /**
     * @return array
     */
    protected function getProductConcreteConstraints(): array
    {
        return [new All([
            new Collection(
                [
                    ProductConcreteMapper::FIELD_NAME => [
                        new NotBlank(),
                    ],
                    ProductConcreteMapper::FIELD_SKU => [
                        new NotBlank(),
                        new SkuRegexConstraint(),
                        new UniqueAbstractSkuConstraint(),
                    ],
                    ProductConcreteMapper::FIELD_SUPER_ATTRIBUTES => [
                        new All($this->getSuperAttributeConstraints()),
                    ],
                ]
            ),
        ])];
    }

    /**
     * @return array
     */
    protected function getSuperAttributeConstraints(): array
    {
        return [new Collection(
            [
                ProductConcreteMapper::FIELD_NAME => [
                    new NotBlank(),
                ],
                ProductConcreteMapper::FIELD_VALUE => [
                    new NotBlank(),
                ],
                ProductConcreteMapper::FIELD_ATTRIBUTE => [
                    new Collection(
                        [
                            ProductConcreteMapper::FIELD_NAME => [
                                new NotBlank(),
                            ],
                            ProductConcreteMapper::FIELD_VALUE => [
                                new NotBlank(),
                            ],
                        ]
                    ),
                ],
            ]
        )];
    }
}
