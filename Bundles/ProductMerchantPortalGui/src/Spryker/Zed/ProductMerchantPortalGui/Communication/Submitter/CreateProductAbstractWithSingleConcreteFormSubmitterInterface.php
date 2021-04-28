<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Submitter;

use Symfony\Component\Form\FormInterface;

interface CreateProductAbstractWithSingleConcreteFormSubmitterInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $createProductAbstractWithSingleConcreteForm
     *
     * @return int
     */
    public function executeFormSubmission(FormInterface $createProductAbstractWithSingleConcreteForm): int;
}
