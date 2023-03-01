<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Spryker\Zed\Discount\Communication\Form\Constraint\Sequentially;
use Spryker\Zed\Gui\Communication\Form\Type\FormattedNumberType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface getRepository()
 */
class VoucherForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

    /**
     * @var string
     */
    public const FIELD_QUANTITY = 'quantity';

    /**
     * @var string
     */
    public const FIELD_CUSTOM_CODE = 'custom_code';

    /**
     * @var string
     */
    public const FIELD_RANDOM_GENERATED_CODE_LENGTH = 'random_generated_code_length';

    /**
     * @var string
     */
    public const FIELD_MAX_NUMBER_OF_USES = 'max_number_of_uses';

    /**
     * @var string
     */
    public const FIELD_ID_DISCOUNT = 'id_discount';

    /**
     * @var string
     */
    protected const FIELD_QUANTITY_ERROR_MESSAGE = 'Invalid entry. Please enter an integer %min%-%max%';

    /**
     * @var string
     */
    protected const FIELD_QUANTITY_HELP_MESSAGE = 'Enter an integer %min%-%max%.';

    /**
     * @var string
     */
    protected const PARAM_QUANTITY_MESSAGE_MIN = '%min%';

    /**
     * @var string
     */
    protected const PARAM_QUANTITY_MESSAGE_MAX = '%max%';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, string> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addQuantityField($builder, $options)
            ->addCustomCodeField($builder)
            ->addRandomGeneratedCodeLength($builder)
            ->addMaxNumberOfUsesField($builder)
            ->addIdDiscount($builder)
            ->addSubmitButton($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => DiscountVoucherTransfer::class,
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, string> $options
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder, array $options)
    {
        $minValue = $this->getConfig()->getVoucherCodesQuantityMinValue();
        $maxValue = $this->getConfig()->getVoucherCodesQuantityMaxValue();

        $quantityHelpMessage = $this->getFactory()
            ->getTranslatorFacade()
            ->trans(static::FIELD_QUANTITY_HELP_MESSAGE, [
                static::PARAM_QUANTITY_MESSAGE_MIN => $minValue,
                static::PARAM_QUANTITY_MESSAGE_MAX => $maxValue,
            ]);

        $builder->add(static::FIELD_QUANTITY, FormattedNumberType::class, [
            'label' => 'Quantity',
            'locale' => $options[static::OPTION_LOCALE],
            'help' => $quantityHelpMessage,
            'attr' => [
                'min' => $minValue,
                'max' => $maxValue,
            ],
            'constraints' => $this->getQuantityFiledConstraints($minValue, $maxValue),
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
            ],
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
            ],
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
            ],
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
     * @return array<int>
     */
    protected function createCodeLengthRangeList()
    {
        $range = range(3, 10);

        return array_combine(array_values($range), $range);
    }

    /**
     * @param int $minValue
     * @param int $maxValue
     *
     * @return list<\Symfony\Component\Validator\Constraint>
     */
    protected function getQuantityFiledConstraints(int $minValue, int $maxValue): array
    {
        $quantityRangeErrorMessage = $this->getFactory()
            ->getTranslatorFacade()
            ->trans(static::FIELD_QUANTITY_ERROR_MESSAGE, [
                static::PARAM_QUANTITY_MESSAGE_MIN => $minValue,
                static::PARAM_QUANTITY_MESSAGE_MAX => $maxValue,
            ]);

        $constraints = [
            'constraints' => [
                new Range([
                    'min' => $minValue,
                    'max' => $maxValue,
                    'notInRangeMessage' => $quantityRangeErrorMessage,
                    'invalidMessage' => $quantityRangeErrorMessage,
                ]),
                new Regex([
                    'pattern' => '/^\d+$/',
                    'message' => $quantityRangeErrorMessage,
                ]),
            ],
        ];

        return [
            new Sequentially($constraints),
        ];
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'discount_voucher';
    }
}
