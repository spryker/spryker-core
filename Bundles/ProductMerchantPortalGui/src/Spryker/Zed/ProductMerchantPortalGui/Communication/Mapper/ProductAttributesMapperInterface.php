<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Symfony\Component\Form\FormErrorIterator;

interface ProductAttributesMapperInterface
{
    /**
     * @param \Symfony\Component\Form\FormErrorIterator $errors
     * @param array $attributesInitialData
     *
     * @return string[][]
     */
    public function mapErrorsToAttributesData(FormErrorIterator $errors, array $attributesInitialData): array;
}
