<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete;

use Spryker\Zed\ProductManagement\Communication\Form\Product\PriceForm as AbstractPriceForm;
use Symfony\Component\Form\FormBuilderInterface;

class PriceForm extends AbstractPriceForm
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'PriceConcreteForm';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addPriceFieldCollection($builder, $options);
    }

}
