<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Form\Expander;

use Symfony\Component\Form\FormBuilderInterface;

interface CreateSspInquiryFormExpanderInterface
{
    /**
     * @return bool
     */
    public function isApplicable(): bool;

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void;
}
