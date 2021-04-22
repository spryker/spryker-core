<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Validator;

use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\SkuRegexConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\UniqueAbstractSkuConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapper;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\External\ProductMerchantPortalGuiToValidationAdapterInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductConcreteValidator
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
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateConcreteProducts(array $concreteProducts): ValidationResponseTransfer
    {
        $constraintViolationList = $this->validationAdapter
            ->createValidator()
            ->validate($concreteProducts, $this->getProductConcreteConstraints());

        $validationResponseTransfer = (new ValidationResponseTransfer())
            ->setIsSuccess(false);

        if ($constraintViolationList->count() === 0) {
            return $validationResponseTransfer->setIsSuccess(true);
        }

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            $validationErrorTransfer = (new ValidationErrorTransfer())
                ->setMessage($constraintViolation->getMessage())
                ->setPropertyPath($constraintViolation->getPropertyPath())
                ->setInvalidValue($constraintViolation->getInvalidValue())
                ->setRoot($constraintViolation->getRoot());

            $validationResponseTransfer->addValidationError($validationErrorTransfer);
        }

        return $validationResponseTransfer;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Collection
     */
    protected function getProductConcreteConstraints(): Collection
    {
        return new Collection(
            [
                ProductConcreteMapper::FIELD_NAME => [
                    new NotBlank(),
                ],
                ProductConcreteMapper::FIELD_SKU => [
                    new NotBlank(),
                    new SkuRegexConstraint(),
                    new UniqueAbstractSkuConstraint(),
                ],
                ProductConcreteMapper::FIELD_SUPER_ATTRIBUTES => new Collection(
                    [
                        ProductConcreteMapper::FIELD_KEY => [
                            new NotBlank(),
                        ],
                        ProductConcreteMapper::FIELD_SUPER_ATTRIBUTES => [
                            new NotBlank(),
                        ],
                    ]
                ),
            ],
        );
    }
}
