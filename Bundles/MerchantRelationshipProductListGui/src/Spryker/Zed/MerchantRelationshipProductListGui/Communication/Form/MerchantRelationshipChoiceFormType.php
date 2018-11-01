<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\Form;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MerchantRelationshipChoiceFormType extends AbstractType
{
    public const OPTION_VALUES_MERCHANT_RELATIONSHIP_CHOICES = 'merchant_relationship_choices';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function buildForm(FormBuilderInterface $builder, array $options): self
    {
        $this->addFkMerchantRelationshipField($builder, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_VALUES_MERCHANT_RELATIONSHIP_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addFkMerchantRelationshipField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(ProductListTransfer::FK_MERCHANT_RELATIONSHIP, ChoiceType::class, [
            'label' => 'Merchant relationship',
            'placeholder' => 'Select one',
            'required' => true,
            'choices' => $options[static::OPTION_VALUES_MERCHANT_RELATIONSHIP_CHOICES],
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }
}
