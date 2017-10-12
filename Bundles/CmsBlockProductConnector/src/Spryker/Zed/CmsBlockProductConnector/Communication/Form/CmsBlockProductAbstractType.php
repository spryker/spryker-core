<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsBlockProductAbstractType extends AbstractType
{
    const FIELD_ID_CMS_BLOCK = 'id_cms_block';
    const FIELD_ID_PRODUCT_ABSTRACTS = 'id_product_abstracts';

    const OPTION_PRODUCT_ABSTRACT_ARRAY = 'option-product-abstracts';

    /**
     * @return string
     */
    public function getName()
    {
        return 'products';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addProductsAbstractField($builder, $options[static::OPTION_PRODUCT_ABSTRACT_ARRAY]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_PRODUCT_ABSTRACT_ARRAY);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addProductsAbstractField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_ID_PRODUCT_ABSTRACTS, new Select2ComboBoxType(), [
            'label' => 'Products',
            'choices' => $choices,
            'multiple' => true,
            'required' => false,
        ]);

        return $this;
    }
}
