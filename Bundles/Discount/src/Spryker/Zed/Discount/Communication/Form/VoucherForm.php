<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Spryker\Zed\Discount\Communication\Form\Validators\MaximumCalculatedRangeValidator;
use Spryker\Zed\Discount\DiscountConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class VoucherForm extends AbstractType
{

    const FIELD_DISCOUNT_VOUCHER_POOL = 'fk_discount_voucher_pool';
    const FIELD_QUANTITY = 'quantity';
    const FIELD_MAX_NUMBER_OF_USES = 'max_number_of_uses';
    const FIELD_CUSTOM_CODE = 'custom_code';
    const FIELD_CODE_LENGTH = 'code_length';

    const OPTION_DISCOUNT_VOUCHER_POOL_CHOICES = 'discount_voucher_pool_choices';
    const OPTION_CODE_LENGTH_CHOICES = 'code_length_choices';
    const OPTION_IS_MULTIPLE = 'is_multiple';

    const ONE_VOUCHER = 1;
    const MINIMUM_VOUCHERS_TO_GENERATE = 2;

    /**
     * @var \Spryker\Zed\Discount\DiscountConfig
     */
    protected $discountConfig;

    /**
     * @param \Spryker\Zed\Discount\DiscountConfig $discountConfig
     */
    public function __construct(DiscountConfig $discountConfig)
    {
        $this->discountConfig = $discountConfig;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'voucher';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::OPTION_DISCOUNT_VOUCHER_POOL_CHOICES);
        $resolver->setRequired(self::OPTION_CODE_LENGTH_CHOICES);
        $resolver->setRequired(self::OPTION_IS_MULTIPLE);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options[self::OPTION_IS_MULTIPLE]) {
            $this->addQuantityField($builder);
        }

        $this
            ->addCustomCodeField($builder)
            ->addCodeLengthField($builder, $options[self::OPTION_CODE_LENGTH_CHOICES])
            ->addMaxNumberOfUsesField($builder)
            ->addFkDiscountVoucherPoolField($builder, $options[self::OPTION_DISCOUNT_VOUCHER_POOL_CHOICES]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_QUANTITY, 'text', [
            'label' => 'Quantity',
            'constraints' => [
                new NotBlank(),
                new GreaterThan(1),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addFkDiscountVoucherPoolField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_DISCOUNT_VOUCHER_POOL, 'choice', [
            'label' => 'Voucher',
            'placeholder' => 'Select one',
            'choices' => $choices,
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
    protected function addMaxNumberOfUsesField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_MAX_NUMBER_OF_USES, 'number', [
            'label' => 'Max number of uses (0 = Infinite usage)',
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
        $builder->add(self::FIELD_CUSTOM_CODE, 'text', [
            'label' => 'Custom Code',
            'attr' => [
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'Add [code] template to position generated code',
                'help' => 'Please enter a string that will be used as custom code, the string code can be used to put the code in a certain position, e.g. "summer-[code]-special"',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addCodeLengthField(FormBuilderInterface $builder, array $choices)
    {
        $maxAllowedCodeCharactersLength = $this->discountConfig->getAllowedCodeCharactersLength();
        $codeLengthValidator = new MaximumCalculatedRangeValidator($maxAllowedCodeCharactersLength);

        $builder->add(self::FIELD_CODE_LENGTH, 'choice', [
            'label' => 'Random Generated Code Length',
            'choices' => $choices,
            'constraints' => [
                new Callback([
                    'methods' => [
                        function ($length, ExecutionContextInterface $context) use ($codeLengthValidator) {
                            $formData = $context->getRoot()->getData();

                            if (empty($formData[self::FIELD_CUSTOM_CODE]) && $length < 1) {
                                $context->addViolation('Please add a custom code or select a length for code to be generated');

                                return;
                            }

                            if ($codeLengthValidator->getPossibleCodeCombinationsCount($length) < $formData[self::FIELD_QUANTITY]) {
                                $context->addViolation('The quantity of required codes is to high regarding the code length');

                                return;
                            }
                        },
                    ],
                ]),
            ],
        ]);

        return $this;
    }

}
