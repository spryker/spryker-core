<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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