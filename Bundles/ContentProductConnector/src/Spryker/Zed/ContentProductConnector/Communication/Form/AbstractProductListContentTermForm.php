<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductConnector\Communication\Form;

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

class AbstractProductListContentTermForm extends AbstractType
{
    public const FIELD_SKUS = 'skus';

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
                /** @var \Generated\Shared\Transfer\ContentAbstractProductListTransfer $contentAbstractProductList */
                $contentAbstractProductList = $form->getNormData();

                foreach ($contentAbstractProductList->getSkus() as $sku) {
                    if (!empty($sku)) {
                        return [Constraint::DEFAULT_GROUP];
                    }
                }

                return [];
            },
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
            'entry_options' => [
                'label' => false,
                'attr' => [
                    'placeholder' => 'sku',
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
