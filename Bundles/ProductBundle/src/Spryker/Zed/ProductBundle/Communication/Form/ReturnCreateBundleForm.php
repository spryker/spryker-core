<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductBundle\Communication\Form\DataProvider\ProductBundleReturnCreateFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesReturnGui\SalesReturnGuiConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 */
class ReturnCreateBundleForm extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            ProductBundleReturnCreateFormDataProvider::OPTION_RETURN_REASONS,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->addReturnBundleItemsField($builder, $options);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addReturnBundleItemsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            ProductBundleReturnCreateFormDataProvider::FIELD_RETURN_BUNDLE_ITEMS,
            CollectionType::class,
            [
                'entry_type' => ReturnCreateBundleItemsSubForm::class,
                'entry_options' => [
                    ProductBundleReturnCreateFormDataProvider::OPTION_RETURN_REASONS => $options[ProductBundleReturnCreateFormDataProvider::OPTION_RETURN_REASONS],
                ],
                'label' => false,
            ]
        );

        return $this;
    }
}
