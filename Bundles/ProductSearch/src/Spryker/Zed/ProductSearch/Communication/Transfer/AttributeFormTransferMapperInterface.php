<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Transfer;

use Symfony\Component\Form\FormInterface;

interface AttributeFormTransferMapperInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $attributeForm
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    public function createTransfer(FormInterface $attributeForm);
}
