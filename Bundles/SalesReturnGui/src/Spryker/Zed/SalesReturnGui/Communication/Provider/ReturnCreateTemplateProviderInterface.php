<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Provider;

use Generated\Shared\Transfer\OrderTransfer;
use Symfony\Component\Form\FormInterface;

/**
 * @deprecated Will be removed without replacement. Exists only for BC reasons.
 */
interface ReturnCreateTemplateProviderInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface<mixed> $returnCreateForm
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<string, mixed>
     */
    public function provide(FormInterface $returnCreateForm, OrderTransfer $orderTransfer): array;
}
