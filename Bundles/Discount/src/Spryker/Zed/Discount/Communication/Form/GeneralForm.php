<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Communication\Form\Constraint\Sequentially;
use Spryker\Zed\Discount\Communication\Form\Constraint\UniqueDiscountName;
use Spryker\Zed\Gui\Communication\Form\Type\FormattedNumberType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface getRepository()
 */
class GeneralForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_STORE_RELATION = 'store_relation';

    /**
     * @var string
     */
    public const FIELD_DISCOUNT_TYPE = 'discount_type';

    /**
     * @var string
     */
    public const FIELD_DISPLAY_NAME = 'display_name';

    /**
     * @var string
     */
    public const FIELD_DESCRIPTION = 'description';

    /**
     * @var string
     */
    public const FIELD_VALID_FROM = 'valid_from';

    /**
     * @var string
     */
    public const FIELD_VALID_TO = 'valid_to';

    /**
     * @var string
     */
    public const FIELD_IS_EXCLUSIVE = 'is_exclusive';

    /**
     * @var string
     */
    public const NON_EXCLUSIVE = 'Non-Exclusive';

    /**
     * @var string
     */
    public const EXCLUSIVE = 'Exclusive';

    /**
     * @var string
     */
    protected const FIELD_PRIORITY = 'priority';

    /**
     * @var string
     */
    protected const FIELD_PRIORITY_LABEL = 'Priority';

    /**
     * @var string
     */
    protected const FIELD_PRIORITY_ERROR_MESSAGE = 'Invalid entry. Please enter an integer %min%-%max%';

    /**
     * @var string
     */
    protected const FIELD_PRIORITY_HELP_MESSAGE = 'Enter an integer %min%-%max%. Discounts are calculated in sequential order, starting from %min%. The default value is %max%.';

    /**
     * @var string
     */
    protected const FORMAT_DATE_TIME = 'dd.MM.yyyy HH:mm';

    /**
     * @var string
     */
    protected const OPTION_LOCALE = 'locale';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addStoreRelationField($builder)
            ->addDiscountType($builder)
            ->addDisplayNameField($builder)
            ->addDescriptionField($builder)
            ->addExclusive($builder)
            ->addValidFromField($builder)
            ->addValidToField($builder);

        if ($this->getRepository()->hasPriorityField()) {
            $this->addPriorityField($builder, $options);
        }
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
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStoreRelationField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_STORE_RELATION,
            $this->getStoreRelationFormTypePlugin()->getType(),
            [
                'label' => false,
            ],
        );

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function getStoreRelationFormTypePlugin()
    {
        return $this->getFactory()->getStoreRelationFormTypePlugin();
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
            'choices' => array_flip($this->getVoucherChoices()),
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @return array<string>
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
            'label' => 'Name',
            'constraints' => [
                new NotBlank(),
                new UniqueDiscountName([
                    UniqueDiscountName::OPTION_DISCOUNT_QUERY_CONTAINER => $this->getQueryContainer(),
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
            ],
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
            'choices' => array_flip([
                static::NON_EXCLUSIVE,
                static::EXCLUSIVE,
            ]),
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
        $builder->add(static::FIELD_VALID_FROM, DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Valid From (Time in UTC)',
            'html5' => false,
            'format' => static::FORMAT_DATE_TIME,
            'input' => 'string',
            'required' => true,
            'attr' => [
                'class' => 'datetimepicker safe-datetime',
            ],
            'constraints' => [
                new NotBlank(),
                new DateTime(),
                new LessThan($this->getConfig()->getMaxAllowedDatetime()),
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
        $builder->add(static::FIELD_VALID_TO, DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Valid To (Time in UTC)',
            'html5' => false,
            'format' => static::FORMAT_DATE_TIME,
            'input' => 'string',
            'required' => true,
            'attr' => [
                'class' => 'datetimepicker safe-datetime',
            ],
            'constraints' => [
                new NotBlank(),
                new DateTime(),
                new GreaterThan([
                    'propertyPath' => sprintf('parent.all[%s].data', static::FIELD_VALID_FROM),
                ]),
                new LessThan($this->getConfig()->getMaxAllowedDatetime()),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addPriorityField(FormBuilderInterface $builder, array $options)
    {
        $minValue = $this->getConfig()->getPriorityMinValue();
        $maxValue = $this->getConfig()->getPriorityMaxValue();

        $translatorFacade = $this->getFactory()->getTranslatorFacade();

        $priorityRangeErrorMessage = $translatorFacade->trans(static::FIELD_PRIORITY_ERROR_MESSAGE, [
            '%min%' => $minValue,
            '%max%' => $maxValue,
        ]);

        $constraints = [
            'constraints' => [
                new Range([
                    'min' => $minValue,
                    'max' => $maxValue,
                    'notInRangeMessage' => $priorityRangeErrorMessage,
                    'invalidMessage' => $priorityRangeErrorMessage,
                ]),
            ],
        ];

        $builder->add(static::FIELD_PRIORITY, FormattedNumberType::class, [
            'label' => static::FIELD_PRIORITY_LABEL,
            'locale' => $options[static::OPTION_LOCALE],
            'required' => false,
            'help' => $translatorFacade->trans(static::FIELD_PRIORITY_HELP_MESSAGE, [
                '%min%' => $minValue,
                '%max%' => $maxValue,
            ]),
            'attr' => [
                'min' => $minValue,
                'max' => $maxValue,
            ],
            'empty_data' => (string)$maxValue,
            'constraints' => [
                new Sequentially($constraints),
            ],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'discount_general';
    }
}
