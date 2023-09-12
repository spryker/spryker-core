<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander;

use Symfony\Component\Form\FormBuilderInterface;

interface ServiceProductOfferFormExpanderInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface;
}
