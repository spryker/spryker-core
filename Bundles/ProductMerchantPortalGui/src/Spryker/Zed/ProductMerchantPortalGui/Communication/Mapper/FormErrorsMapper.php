<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Symfony\Component\Form\FormInterface;

class FormErrorsMapper implements FormErrorsMapperInterface
{
    /**
     * @var string
     */
    protected const PROPERTY_PATH_PATTERN = '/(?<=\[).+?(?=\])/';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\AddProductConcreteForm::FIELD_ATTRIBUTES
     *
     * @var string
     */
    protected const FIELD_ATTRIBUTES = 'attributes';

    /**
     * @param \Symfony\Component\Form\FormInterface<mixed> $addProductConcreteForm
     * @param array<int, mixed> $errors
     *
     * @return array<int, mixed>
     */
    public function mapAddProductConcreteFormErrorsToErrorsData(
        FormInterface $addProductConcreteForm,
        array $errors
    ): array {
        /** @var \Symfony\Component\Form\FormError $error */
        foreach ($addProductConcreteForm->getErrors() as $error) {
            preg_match_all(static::PROPERTY_PATH_PATTERN, $error->getCause()->getPropertyPath(), $matches);

            if (!isset($matches[0])) {
                continue;
            }

            $matches = $matches[0];
            $index = $matches[1] ?? null;
            $field = $matches[2] ?? null;

            if ($index === null || $field === null) {
                continue;
            }

            $errors[(int)$index]['errors'][$field] = $error->getMessage();
        }

        $errors = $this->addEmptyErrors($errors);

        return $errors;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface<mixed> $addProductConcreteForm
     * @param array<int, mixed> $errors
     *
     * @return array<int, mixed>
     */
    public function mapAddProductConcreteFormAttributesErrorsToErrorsData(
        FormInterface $addProductConcreteForm,
        array $errors
    ): array {
        /** @var \Symfony\Component\Form\FormError $error */
        foreach ($addProductConcreteForm->getErrors() as $error) {
            preg_match_all(static::PROPERTY_PATH_PATTERN, $error->getCause()->getPropertyPath(), $matches);

            if (!isset($matches[0])) {
                continue;
            }

            $matches = $matches[0];

            if (!isset($matches[0]) || $matches[0] !== static::FIELD_ATTRIBUTES) {
                continue;
            }

            $index = $matches[1] ?? null;

            if ($index === null) {
                continue;
            }

            $errors[(int)$index]['error'] = $error->getMessage();
        }

        $errors = $this->addEmptyErrors($errors);

        return $errors;
    }

    /**
     * @param array<int, mixed> $errors
     *
     * @return array<int, mixed>
     */
    protected function addEmptyErrors(array $errors): array
    {
        if (!$errors) {
            return $errors;
        }

        $lastKey = (int)max(array_keys($errors));
        for ($i = 0; $i <= $lastKey; $i++) {
            if (!isset($errors[$i])) {
                $errors[$i] = [];
            }
        }

        ksort($errors);

        return $errors;
    }
}
