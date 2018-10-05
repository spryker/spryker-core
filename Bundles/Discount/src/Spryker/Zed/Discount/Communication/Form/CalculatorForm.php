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
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\Money\Communication\Form\Type\MoneyCollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
 */
class CalculatorForm extends AbstractType
{
    public const FIELD_AMOUNT = 'amount';
    public const FIELD_CALCULATOR_PLUGIN = 'calculator_plugin';
    public const FIELD_COLLECTOR_QUERY_STRING = 'collector_query_string';
    public const FIELD_COLLECTOR_TYPE_CHOICE = 'collector_type_choice';

    public const OPTION_COLLECTOR_TYPE_CHOICES = 'collector_type_choices';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCalculatorType($builder)
            ->addCalculatorInputs($builder)
            ->addDiscountCollectorStrategyTypeSelector($builder)
            ->addCollectorQueryString($builder);

        $builder->addModelTransformer($this->getFactory()->createCalculatorAmountTransformer());

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $this->addCalculatorPluginAmountValidators($event->getForm(), $event->getData());
            }
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                return [
                    Constraint::DEFAULT_GROUP,
                    $form->getData()->getCollectorStrategyType(),
                    $this->getCalculatorInputType($form->getData()->getCalculatorPlugin()),
                ];
            },
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
     * @param array $data
     *
     * @return void
     */
    protected function addCalculatorPluginAmountValidators(FormInterface $form, array $data)
    {
        if (empty($data[static::FIELD_CALCULATOR_PLUGIN])) {
            return;
        }

        $calculatorPlugin = $this->getCalculatorPlugin($data[static::FIELD_CALCULATOR_PLUGIN]);

        $amountField = $form->get(static::FIELD_AMOUNT);
        $constraints = $amountField->getConfig()->getOption('constraints');
        $constraints = array_merge($constraints, $calculatorPlugin->getAmountValidators());
        $form->remove(static::FIELD_AMOUNT);
        $this->addAmountField($form, ['constraints' => $constraints]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCalculatorInputs(FormBuilderInterface $builder)
    {
        $this->addAmountField($builder)
            ->addMoneyValueCollectionType($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface|\Symfony\Component\Form\FormInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAmountField($builder, array $options = [])
    {
        $defaultOptions = [
            'label' => 'Value',
            'attr' => [
                'class' => 'input-group',
            ],
            'constraints' => [
                new NotBlank([
                    'groups' => DiscountConstants::CALCULATOR_DEFAULT_INPUT_TYPE,
                ]),
            ],
        ];

        $builder->add(
            static::FIELD_AMOUNT,
            TextType::class,
            array_merge($defaultOptions, $options)
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
        $builder->add(
            DiscountCalculatorTransfer::MONEY_VALUE_COLLECTION,
            MoneyCollectionType::class,
            [
                MoneyCollectionType::OPTION_AMOUNT_PER_STORE => false,
            ]
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
            'choices_as_values' => true,
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
            'choices_as_values' => true,
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
                    ]
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
                $pluginName
            ));
        }

        return $calculatorPlugins[$pluginName];
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'discount_calculator';
    }
}
