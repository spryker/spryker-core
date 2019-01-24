<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\BusinessOnBehalfGuiExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

interface CustomerBusinessUnitAttachFormExpanderPluginInterface
{
    /**
     * Specification:
     *  - Expands CustomerBusinessUnitAttachForm.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface;
}
