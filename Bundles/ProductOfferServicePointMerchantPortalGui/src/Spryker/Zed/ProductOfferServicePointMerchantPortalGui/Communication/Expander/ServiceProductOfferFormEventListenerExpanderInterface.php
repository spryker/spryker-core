<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander;

use Symfony\Component\Form\FormBuilderInterface;

interface ServiceProductOfferFormEventListenerExpanderInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface;
}
