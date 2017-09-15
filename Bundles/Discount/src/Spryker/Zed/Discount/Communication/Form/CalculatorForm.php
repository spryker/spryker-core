<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\DiscountFacadeInterface;
use Spryker\Zed\Discount\Business\Exception\CalculatorException;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaProviderFactory;
use Spryker\Zed\Discount\Communication\Form\Constraint\QueryString;
use Spryker\Zed\Discount\Communication\Form\DataProvider\CalculatorFormDataProvider;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class CalculatorForm extends AbstractType
{

    const FIELD_AMOUNT = 'amount';
    const FIELD_CALCULATOR_PLUGIN = 'calculator_plugin';
    const FIELD_COLLECTOR_QUERY_STRING = 'collector_query_string';
    const FIELD_COLLECTOR_TYPE_CHOICE = 'collector_type_choice';

    const OPTION_COLLECTOR_TYPE_CHOICES = 'collector_type_choices';
    const VALIDATION_GROUP_CALCULATOR_DEFAULT_TYPE = 'validation_group_calculator_default_type';

    /**
     * @var \Spryker\Zed\Discount\Communication\Form\DataProvider\CalculatorFormDataProvider
     */
    protected $calculatorFormDataProvider;

    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    protected $calculatorPlugins;

    /**
     * @var \Symfony\Component\Form\DataTransformerInterface|\Spryker\Zed\Discount\Communication\Form\Transformer\CalculatorAmountTransformer
     */
    protected $calculatorAmountTransformer;

    /**
     * @param \Spryker\Zed\Discount\Communication\Form\DataProvider\CalculatorFormDataProvider $calculatorFormDataProvider
     * @param \Spryker\Zed\Discount\Business\DiscountFacadeInterface $discountFacade
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[] $calculatorPlugins
     * @param \Symfony\Component\Form\DataTransformerInterface|\Spryker\Zed\Discount\Communication\Form\Transformer\CalculatorAmountTransformer $calculatorAmountTransformer
     */
    public function __construct(
        CalculatorFormDataProvider $calculatorFormDataProvider,
        DiscountFacadeInterface $discountFacade,
        array $calculatorPlugins,
        DataTransformerInterface $calculatorAmountTransformer
    ) {
        $this->calculatorFormDataProvider = $calculatorFormDataProvider;
        $this->discountFacade = $discountFacade;
        $this->calculatorPlugins = $calculatorPlugins;
        $this->calculatorAmountTransformer = $calculatorAmountTransformer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCalculatorType($builder)
            ->addAmountField($builder)
            ->addMoneyAmountFields($builder)
            ->addDiscountCollectorStrategyTypeSelector($builder)
            ->addCollectorQueryString($builder);

        $builder->addModelTransformer($this->calculatorAmountTransformer);

        $builder
            ->addEventListener(
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
                $validationGroup = $form->getData()->getCalculatorPlugin() == 'PLUGIN_CALCULATOR_PERCENTAGE' ? static::VALIDATION_GROUP_CALCULATOR_DEFAULT_TYPE :  MoneyAmountForm::VALIDATION_GROUP_CALCULATOR_MONEY_TYPE;

                return [
                    Constraint::DEFAULT_GROUP,
                    $form->getData()->getCollectorStrategyType(),
                    $validationGroup,
                ];
            },
        ]);
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
        if (!$calculatorPlugin) {
            return;
        }

        $amountField = $form->get(static::FIELD_AMOUNT);
        $constraints = $amountField->getConfig()->getOption('constraints');
        foreach ($calculatorPlugin->getAmountValidators() as $constraint) {
            $constraint->groups = [static::VALIDATION_GROUP_CALCULATOR_DEFAULT_TYPE];
        }
        $constraints = array_merge($constraints, $calculatorPlugin->getAmountValidators());
        $form->remove(static::FIELD_AMOUNT);
        $this->addAmountField(
            $form,
            [
                'constraints' => $constraints,
            ]
        );
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
            'label' => 'Amount',
            'attr' => [
                'class' => 'input-group',
            ],
            'constraints' => [
                new NotBlank(['groups' => static::VALIDATION_GROUP_CALCULATOR_DEFAULT_TYPE])
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
    protected function addMoneyAmountFields(FormBuilderInterface $builder)
    {
        $builder->add(
            DiscountCalculatorTransfer::DISCOUNT_MONEY_AMOUNTS,
            CollectionType::class, [
                'type' => $this->getFactory()->createMoneyAmountFormType(),
                'entry_options' => [
                    'data_class' => DiscountMoneyAmountTransfer::class,
                ],
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
        $builder->add(DiscountCalculatorTransfer::COLLECTOR_STRATEGY_TYPE, 'choice', [
            'expanded' => true,
            'multiple' => false,
            'label' => 'Discount collection type',
            'choices' => $this->calculatorFormDataProvider->getOptions()[static::OPTION_COLLECTOR_TYPE_CHOICES],
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
        $builder->add(static::FIELD_CALCULATOR_PLUGIN, 'choice', [
            'label' => 'Calculator type',
            'placeholder' => 'Select one',
            'choices' => $this->calculatorFormDataProvider->getData()[static::FIELD_CALCULATOR_PLUGIN],
            'required' => true,
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

        $builder->add(static::FIELD_COLLECTOR_QUERY_STRING, 'textarea', [
            'label' => $label,
            'constraints' => [
                new NotBlank(['groups' => DiscountConstants::DISCOUNT_COLLECTOR_STRATEGY_QUERY_STRING]),
                new QueryString([
                    QueryString::OPTION_DISCOUNT_FACADE => $this->discountFacade,
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
        if (isset($this->calculatorPlugins[$pluginName])) {
            return $this->calculatorPlugins[$pluginName];
        }

        throw new CalculatorException(sprintf(
            'Calculator plugin with name "%s" not found.
            Have you added it to DiscountDependencyProvider::getAvailableCalculatorPlugins plugin stack?',
            $pluginName
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'discount_calculator';
    }

}
