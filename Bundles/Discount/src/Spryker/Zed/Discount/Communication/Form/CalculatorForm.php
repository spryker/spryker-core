<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\Exception\CalculatorException;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaProviderFactory;
use Spryker\Zed\Discount\Communication\Form\Constraint\QueryString;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginWithAmountInputTypeInterface;
use Spryker\Zed\Gui\Communication\Form\Type\FormattedNumberType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\Money\Communication\Form\Type\MoneyCollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface getRepository()
 */
class CalculatorForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_AMOUNT = 'amount';

    /**
     * @var string
     */
    public const FIELD_CALCULATOR_PLUGIN = 'calculator_plugin';

    /**
     * @var string
     */
    public const FIELD_COLLECTOR_QUERY_STRING = 'collector_query_string';

    /**
     * @var string
     */
    public const FIELD_COLLECTOR_TYPE_CHOICE = 'collector_type_choice';

    /**
     * @var string
     */
    public const OPTION_COLLECTOR_TYPE_CHOICES = 'collector_type_choices';

    /**
     * @var string
     */
    protected const OPTION_AMOUNT_PER_STORE = 'amount_per_store';

    /**
     * @var string
     */
    protected const OPTION_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const OPTION_CONSTRAINTS = 'constraints';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCalculatorType($builder)
            ->addCalculatorInputs($builder, $options)
            ->addDiscountCollectorStrategyTypeSelector($builder)
            ->addCollectorQueryString($builder);

        $builder->addModelTransformer($this->getFactory()->createCalculatorAmountTransformer());

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($options) {
                $this->addCalculatorPluginAmountValidators($event->getForm(), $event->getData(), $options);
            },
        );
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
            'validation_groups' => function (FormInterface $form) {
                $formData = $form->getData();
                if (!$formData) {
                    return [Constraint::DEFAULT_GROUP];
                }

                return [
                    Constraint::DEFAULT_GROUP,
                    $formData->getCollectorStrategyType(),
                    $this->getCalculatorInputType($formData->getCalculatorPlugin()),
                ];
            },
            static::OPTION_CONSTRAINTS => [
                new NotBlank([
                    'groups' => DiscountConstants::CALCULATOR_DEFAULT_INPUT_TYPE,
                ]),
            ],
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param string $pluginName
     *
     * @return string
     */
    protected function getCalculatorInputType($pluginName)
    {
        $calculatorPlugin = $this->getCalculatorPlugin($pluginName);
        if ($calculatorPlugin instanceof DiscountCalculatorPluginWithAmountInputTypeInterface) {
            return $calculatorPlugin->getInputType();
        }

        return DiscountConstants::CALCULATOR_DEFAULT_INPUT_TYPE;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function addCalculatorPluginAmountValidators(FormInterface $form, array $data, array $options): void
    {
        if (empty($data[static::FIELD_CALCULATOR_PLUGIN])) {
            return;
        }

        $calculatorPlugin = $this->getCalculatorPlugin($data[static::FIELD_CALCULATOR_PLUGIN]);

        $amountField = $form->get(static::FIELD_AMOUNT);
        $constraints = $amountField->getConfig()->getOption('constraints');
        $constraints = array_merge($constraints, $calculatorPlugin->getAmountValidators());
        $form->remove(static::FIELD_AMOUNT);
        $this->addAmountField($form, array_merge($options, [static::OPTION_CONSTRAINTS => $constraints]));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addCalculatorInputs(FormBuilderInterface $builder, array $options)
    {
        $this->addAmountField($builder, $options)
            ->addMoneyValueCollectionType($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface|\Symfony\Component\Form\FormInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAmountField($builder, array $options)
    {
        $builder->add(
            static::FIELD_AMOUNT,
            FormattedNumberType::class,
            [
                'label' => 'Value',
                'attr' => [
                    'class' => 'input-group',
                ],
                'constraints' => $options[static::OPTION_CONSTRAINTS],
                'locale' => $options[static::OPTION_LOCALE],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMoneyValueCollectionType(FormBuilderInterface $builder)
    {
        $options = [
            static::OPTION_AMOUNT_PER_STORE => false,
        ];

            $builder->add(
                DiscountCalculatorTransfer::MONEY_VALUE_COLLECTION,
                $this->getMoneyValueCollectionType(),
                $options,
            );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDiscountCollectorStrategyTypeSelector(FormBuilderInterface $builder)
    {
        $builder->add(DiscountCalculatorTransfer::COLLECTOR_STRATEGY_TYPE, ChoiceType::class, [
            'expanded' => true,
            'multiple' => false,
            'label' => 'Discount collection type',
            'choices' => array_flip($this->getFactory()->createCalculatorFormDataProvider()->getOptions()[static::OPTION_COLLECTOR_TYPE_CHOICES]),
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
    protected function addCalculatorType(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CALCULATOR_PLUGIN, ChoiceType::class, [
            'label' => 'Calculator type',
            'placeholder' => 'Select one',
            'choices' => array_flip($this->getFactory()->createCalculatorFormDataProvider()->getData()[static::FIELD_CALCULATOR_PLUGIN]),
            'required' => true,
            'choice_attr' => function ($pluginName) {
                return [
                    'data-calculator-input-type' => $this->getCalculatorInputType($pluginName),
                ];
            },
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
    protected function addCollectorQueryString(FormBuilderInterface $builder)
    {
        $label = 'Apply to';

        $builder->add(static::FIELD_COLLECTOR_QUERY_STRING, TextareaType::class, [
            'label' => $label,
            'constraints' => [
                new NotBlank(['groups' => DiscountConstants::DISCOUNT_COLLECTOR_STRATEGY_QUERY_STRING]),
                new QueryString([
                    QueryString::OPTION_DISCOUNT_FACADE => $this->getFacade(),
                    QueryString::OPTION_QUERY_STRING_TYPE => MetaProviderFactory::TYPE_COLLECTOR,
                    'groups' => DiscountConstants::DISCOUNT_COLLECTOR_STRATEGY_QUERY_STRING,
                ]),
            ],
            'attr' => [
                'data-label' => $label . ' *',
                'data-url' => Url::generate(
                    '/discount/query-string/rule-fields',
                    [
                        'type' => MetaProviderFactory::TYPE_COLLECTOR,
                    ],
                )->build(),
            ],
        ]);

        return $this;
    }

    /**
     * @param string $pluginName
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\CalculatorException
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    protected function getCalculatorPlugin($pluginName)
    {
        $calculatorPlugins = $this->getFactory()->getCalculatorPlugins();
        if (!isset($calculatorPlugins[$pluginName])) {
            throw new CalculatorException(sprintf(
                'Calculator plugin with name "%s" not found. Have you added it to DiscountDependencyProvider::getAvailableCalculatorPlugins() plugin stack?',
                $pluginName,
            ));
        }

        return $calculatorPlugins[$pluginName];
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'discount_calculator';
    }

    /**
     * @return string
     */
    protected function getMoneyValueCollectionType(): string
    {
        if ($this->getConfig()->isMoneyCollectionFormTypePluginEnabled()) {
            return $this->getFactory()->getMoneyCollectionFormTypePlugin()->getType();
        }

        return MoneyCollectionType::class;
    }
}
