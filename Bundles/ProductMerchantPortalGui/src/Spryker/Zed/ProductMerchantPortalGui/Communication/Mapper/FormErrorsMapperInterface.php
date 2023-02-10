<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Symfony\Component\Form\FormInterface;

interface FormErrorsMapperInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface<mixed> $addProductConcreteForm
     * @param array<int, mixed> $errors
     *
     * @return array<int, mixed>
     */
    public function mapAddProductConcreteFormErrorsToErrorsData(
        FormInterface $addProductConcreteForm,
        array $errors
    ): array;

    /**
     * @param \Symfony\Component\Form\FormInterface<mixed> $addProductConcreteForm
     * @param array<int, mixed> $errors
     *
     * @return array<int, mixed>
     */
    public function mapAddProductConcreteFormAttributesErrorsToErrorsData(
        FormInterface $addProductConcreteForm,
        array $errors
    ): array;
}
