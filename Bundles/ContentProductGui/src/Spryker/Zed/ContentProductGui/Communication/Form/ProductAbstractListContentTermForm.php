<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class ProductAbstractListContentTermForm extends AbstractType
{
    public const FIELD_SKUS = 'skus';
    public const PLACEHOLDER_SKU = 'sku';

    protected const TEMPLATE_PATH = '@ContentProductGui/ProductAbstractList/product_abstract_list.twig';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                /** @var \Generated\Shared\Transfer\LocalizedContentTransfer $localizedContentTransfer */
                $localizedContentTransfer = $form->getParent()->getData();
                if ($localizedContentTransfer->getFkLocale() === null) {
                    return [Constraint::DEFAULT_GROUP];
                }
                /** @var \Generated\Shared\Transfer\ContentProductAbstractListTransfer $contentProductAbstractList */
                $contentProductAbstractList = $form->getNormData();

                foreach ($contentProductAbstractList->getSkus() as $sku) {
                    if ($sku) {
                        return [Constraint::DEFAULT_GROUP];
                    }
                }

                return [];
            },
            'attr' => [
                'template_path' => static::TEMPLATE_PATH,
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'abstract-product-list';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addSkusField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkusField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SKUS, CollectionType::class, [
            'entry_type' => TextType::class,
            'label' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'label' => false,
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_SKU,
                ],
                'constraints' => $this->getTextFieldConstraints(),
            ],
        ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function getTextFieldConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 100]),
        ];
    }
}
