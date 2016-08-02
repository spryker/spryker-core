<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Symfony\Component\Form\FormBuilderInterface;

class ProductFormEdit extends ProductFormAdd
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'ProductFormEdit';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        /*$this
            ->addSkuField($builder)
            ->addProductAbstractIdHiddenField($builder)
            ->addGeneralLocalizedForms($builder)
            ->addAttributeAbstractForms($builder, $options[self::OPTION_ATTRIBUTE_ABSTRACT])
            ->addPriceForm($builder, $options[self::OPTION_TAX_RATES])
            ->addSeoLocalizedForms($builder, $options)
            ->addImageForm($builder);*/
    }

}
