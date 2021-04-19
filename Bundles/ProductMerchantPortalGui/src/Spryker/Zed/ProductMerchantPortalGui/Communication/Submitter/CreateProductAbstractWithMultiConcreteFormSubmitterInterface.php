<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Submitter;

use Symfony\Component\Form\FormInterface;

interface CreateProductAbstractWithMultiConcreteFormSubmitterInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $createProductAbstractWithMultiConcreteForm
     *
     * @return int
     */
    public function executeFormSubmission(FormInterface $createProductAbstractWithMultiConcreteForm): int;
}
