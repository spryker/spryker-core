<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin\Form;

use Symfony\Component\Form\FormBuilderInterface;

interface DiscountFormExpanderPluginInterface
{
    /**
     * Specification:
     *
     * This method will received builder object from discount form type, you can use it to add new form types.
     * Or return new which for builder object instance.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandFormType(FormBuilderInterface $builder, array $options);
}
