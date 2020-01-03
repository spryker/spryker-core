<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface getRepository()
 */
class VoucherForm extends AbstractType
{
    public const FIELD_QUANTITY = 'quantity';
    public const FIELD_CUSTOM_CODE = 'custom_code';
    public const FIELD_RANDOM_GENERATED_CODE_LENGTH = 'random_generated_code_length';
    public const FIELD_MAX_NUMBER_OF_USES = 'max_number_of_uses';
    public const FIELD_ID_DISCOUNT = 'id_discount';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addQuantityField($builder)
            ->addCustomCodeField($builder)
            ->addRandomGeneratedCodeLength($builder)
            ->addMaxNumberOfUsesField($builder)
            ->addIdDiscount($builder)
            ->addSubmitButton($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_QUANTITY, TextType::class, [
            'label' => 'Quantity',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCustomCodeField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_CUSTOM_CODE,
            TextType::class,
            [
                'required' => false,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRandomGeneratedCodeLength(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_RANDOM_GENERATED_CODE_LENGTH,
            ChoiceType::class,
            [
                'label' => 'Add Random Generated Code Length',
                'placeholder' => 'No additional random characters',
                'required' => false,
                'choices' => array_flip($this->createCodeLengthRangeList()),
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMaxNumberOfUsesField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_MAX_NUMBER_OF_USES,
            TextType::class,
            [
                'label' => 'Max number of uses (0 = Infinite usage)',
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdDiscount(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_DISCOUNT, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSubmitButton(FormBuilderInterface $builder)
    {
        $builder->add('generate', SubmitType::class, [
            'attr' => [
                'class' => 'btn-create',
            ],
        ]);

        return $this;
    }

    /**
     * @return int[]
     */
    protected function createCodeLengthRangeList()
    {
        $range = range(3, 10);

        return array_combine(array_values($range), $range);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'discount_voucher';
    }
}
