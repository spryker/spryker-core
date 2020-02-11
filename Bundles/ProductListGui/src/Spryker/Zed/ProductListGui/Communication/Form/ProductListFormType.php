<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form;

use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 */
class ProductListFormType extends AbstractType
{
    public const FIELD_ID_PRODUCT_LIST = ProductListTransfer::ID_PRODUCT_LIST;
    public const FIELD_TITLE = ProductListTransfer::TITLE;
    public const FIELD_TYPE = ProductListTransfer::TYPE;

    public const BLOCK_PREFIX = 'productList';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ProductListTransfer::class,
            'label' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdProductListField($builder)
            ->addTitleField($builder)
            ->addTypeFiled($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductListField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_LIST, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTitleField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_TITLE,
            TextType::class,
            [
                'label' => 'Title',
                'constraints' => [
                    new Required(),
                    new NotBlank(),
                    new Length(['max' => 255]),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTypeFiled(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TYPE, ChoiceType::class, [
            'label' => 'Type',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Whitelist' => SpyProductListTableMap::COL_TYPE_WHITELIST,
                'Blacklist' => SpyProductListTableMap::COL_TYPE_BLACKLIST,
            ],
            'constraints' => [
                new Required(),
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
