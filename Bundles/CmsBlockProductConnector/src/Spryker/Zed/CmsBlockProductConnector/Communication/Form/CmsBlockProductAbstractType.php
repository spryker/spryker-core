<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockProductConnector\Communication\CmsBlockProductConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface getQueryContainer()
 */
class CmsBlockProductAbstractType extends AbstractType
{
    public const FIELD_ID_CMS_BLOCK = 'id_cms_block';
    public const FIELD_ID_PRODUCT_ABSTRACTS = 'id_product_abstracts';

    public const OPTION_PRODUCT_ABSTRACT_ARRAY = 'option-product-abstracts';

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
        $builder->add(static::FIELD_ID_PRODUCT_ABSTRACTS, Select2ComboBoxType::class, [
            'label' => 'Products',
            'choices' => $choices,
            'multiple' => true,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'products';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
