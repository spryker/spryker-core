<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Symfony\Component\Form\FormInterface;

interface ProductAbstractMapperInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $createProductAbstractWithMultiConcreteForm
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapFormDataToProductAbstractTransfer(
        FormInterface $createProductAbstractWithMultiConcreteForm
    ): ProductAbstractTransfer;
}
