<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\DiscountFacadeInterface;
use Spryker\Zed\Discount\Business\Exception\CalculatorException;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaProviderFactory;
use Spryker\Zed\Discount\Communication\Form\Constraint\QueryString;
use Spryker\Zed\Discount\Communication\Form\DataProvider\CalculatorFormDataProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class CalculatorForm extends AbstractType
{

    const MAX_MONEY_INT = 21474835;
    const MIN_MONEY_INT = 0;

    const FIELD_AMOUNT = 'amount';
    const FIELD_CALCULATOR_PLUGIN = 'calculator_plugin';
    const FIELD_COLLECTOR_QUERY_STRING = 'collector_query_string';
    const FIELD_COLLECTOR_TYPE_CHOICE = 'collector_type_choice';

    const OPTION_COLLECTOR_TYPE_CHOICES = 'collector_type_choices';

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
                return ['Default', $form->getData()->getCollectorStrategyType()];
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
        if (empty($data[self::FIELD_CALCULATOR_PLUGIN])) {
            return;
        }

        $calculatorPlugin = $this->getCalculatorPlugin($data[self::FIELD_CALCULATOR_PLUGIN]);
        if (!$calculatorPlugin) {
            return;
        }

        $amountField = $form->get(self::FIELD_AMOUNT);
        $constraints = $amountField->getConfig()->getOption('constraints');
        $constraints = array_merge($constraints, $calculatorPlugin->getAmountValidators());
        $form->remove(self::FIELD_AMOUNT);
        $this->addAmountField($form, ['constraints' => $constraints]);
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
                new NotBlank(),
                new LessThanOrEqual(static::MAX_MONEY_INT),
                new GreaterThanOrEqual(static::MIN_MONEY_INT),
            ],
        ];

        $builder->add(
            self::FIELD_AMOUNT,
            'money',
            array_merge($defaultOptions, $options)
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
        $builder->add(self::FIELD_CALCULATOR_PLUGIN, 'choice', [
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

        $builder->add(self::FIELD_COLLECTOR_QUERY_STRING, 'textarea', [
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
