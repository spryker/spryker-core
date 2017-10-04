<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Communication\Form\Constraint\UniqueDiscountName;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class GeneralForm extends AbstractType
{

    const FIELD_DISCOUNT_TYPE = 'discount_type';
    const FIELD_DISPLAY_NAME = 'display_name';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_VALID_FROM = 'valid_from';
    const FIELD_VALID_TO = 'valid_to';
    const FIELD_IS_EXCLUSIVE = 'is_exclusive';
    const NON_EXCLUSIVE = 'Non-Exclusive';
    const EXCLUSIVE = 'Exclusive';

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     */
    public function __construct(DiscountQueryContainerInterface $discountQueryContainer)
    {
        $this->discountQueryContainer = $discountQueryContainer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addDiscountType($builder)
            ->addDisplayNameField($builder)
            ->addDescriptionField($builder)
            ->addExclusive($builder)
            ->addValidFromField($builder)
            ->addValidToField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDiscountType(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DISCOUNT_TYPE, ChoiceType::class, [
            'label' => 'Discount Type',
            'choices' => $this->getVoucherChoices(),
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @return string[]
     */
    protected function getVoucherChoices()
    {
        return [
            DiscountConstants::TYPE_CART_RULE => 'Cart rule',
            DiscountConstants::TYPE_VOUCHER => 'Voucher codes',
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDisplayNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DISPLAY_NAME, TextType::class, [
            'label' => 'Name (A unique name that will be displayed to your customers)',
            'constraints' => [
                new NotBlank(),
                new UniqueDiscountName([
                    UniqueDiscountName::OPTION_DISCOUNT_QUERY_CONTAINER => $this->discountQueryContainer,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_DESCRIPTION,
            TextareaType::class,
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
    protected function addExclusive(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_EXCLUSIVE, ChoiceType::class, [
            'expanded' => true,
            'multiple' => false,
            'label' => false,
            'choices' => [
                self::NON_EXCLUSIVE,
                self::EXCLUSIVE,
            ],
            'constraints' => [
                new NotBlank(),
            ],
            'attr' => [
                'class' => 'inline-radio',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VALID_FROM, DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'attr' => [
                'class' => 'datepicker',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidToField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VALID_TO, DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'attr' => [
                'class' => 'datepicker',
            ],
        ]);

        return $this;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'discount_general';
    }

}
