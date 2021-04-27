<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Symfony\Component\Form\FormInterface;

interface CreateReturnFormHandlerInterface
{
    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $returnCreateForm
     *
     * @param \Symfony\Component\Form\FormInterface $returnCreateForm
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function handleForm(FormInterface $returnCreateForm, OrderTransfer $orderTransfer): ReturnResponseTransfer;
}
