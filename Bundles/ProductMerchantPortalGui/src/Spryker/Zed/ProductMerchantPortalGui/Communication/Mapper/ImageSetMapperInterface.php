<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Symfony\Component\Form\FormErrorIterator;

interface ImageSetMapperInterface
{
    /**
     * @param \Symfony\Component\Form\FormErrorIterator<\Symfony\Component\Form\FormError> $errors
     *
     * @return array<int, array<string, mixed>>
     */
    public function mapErrorsToImageSetValidationData(FormErrorIterator $errors): array;
}
