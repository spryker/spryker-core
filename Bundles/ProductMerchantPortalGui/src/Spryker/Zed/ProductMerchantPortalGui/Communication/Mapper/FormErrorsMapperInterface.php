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
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $addProductConcreteForm
     *
     * @param \Symfony\Component\Form\FormInterface $addProductConcreteForm
     * @param mixed[] $errors
     *
     * @return mixed[]
     */
    public function mapAddProductConcreteFormErrorsToErrorsData(
        FormInterface $addProductConcreteForm,
        array $errors
    ): array;

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $addProductConcreteForm
     *
     * @param \Symfony\Component\Form\FormInterface $addProductConcreteForm
     * @param mixed[] $errors
     *
     * @return mixed[]
     */
    public function mapAddProductConcreteFormAttributesErrorsToErrorsData(
        FormInterface $addProductConcreteForm,
        array $errors
    ): array;
}
