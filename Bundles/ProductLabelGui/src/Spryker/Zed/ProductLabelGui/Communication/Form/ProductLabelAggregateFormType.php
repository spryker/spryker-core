<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form;

use Generated\Shared\Transfer\ProductLabelAggregateFormTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ProductLabelGui\Business\ProductLabelGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabelGui\Communication\ProductLabelGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig getConfig()
 */
class ProductLabelAggregateFormType extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ProductLabelAggregateFormTransfer::class,
        ]);
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
            ->addProductLabelSubForm($builder)
            ->addRelatedProductSubForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductLabelSubForm(FormBuilderInterface $builder)
    {
        $builder->add(
            ProductLabelAggregateFormTransfer::PRODUCT_LABEL,
            ProductLabelFormType::class,
            [
                'label' => false,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRelatedProductSubForm(FormBuilderInterface $builder)
    {
        $builder->add(
            ProductLabelAggregateFormTransfer::PRODUCT_ABSTRACT_RELATIONS,
            RelatedProductFormType::class,
            [
                'label' => false,
            ]
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'productLabelAggregate';
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
