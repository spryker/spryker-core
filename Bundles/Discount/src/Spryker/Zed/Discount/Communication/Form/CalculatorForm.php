<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Communication\Form\DataProvider\CalculatorFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\Constraint\QueryString;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;

class CalculatorForm extends AbstractType
{
    const FIELD_AMOUNT = 'amount';
    const FIELD_CALCULATOR_PLUGIN = 'calculator_plugin';
    const FIELD_COLLECTOR_QUERY_STRING = 'collector_query_string';

    /**
     * @var CalculatorFormDataProvider
     */
    protected $calculatorFormDataProvider;

    /**
     * @var DiscountFacade
     */
    protected $discountFacade;

    /**
     * @param CalculatorFormDataProvider $calculatorFormDataProvider
     * @param DiscountFacade $discountFacade
     */
    public function __construct(
        CalculatorFormDataProvider $calculatorFormDataProvider,
        DiscountFacade $discountFacade
    )
    {
        $this->calculatorFormDataProvider = $calculatorFormDataProvider;
        $this->discountFacade = $discountFacade;
    }


    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array|string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCalculatorType($builder)
            ->addAmountField($builder)
            ->addCollectorQueryString($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAmountField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_AMOUNT, 'text', [
            'label' => 'Amount*',
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
    protected function addCalculatorType(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CALCULATOR_PLUGIN, 'choice', [
            'label' => 'Calculator type',
            'placeholder' => 'Select one',
            'choices' => $this->calculatorFormDataProvider->getData()[self::FIELD_CALCULATOR_PLUGIN],
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
        $builder->add(self::FIELD_COLLECTOR_QUERY_STRING, 'textarea', [
            'label' => 'Apply to*',
            'constraints' => [
                new NotBlank(),
                new QueryString([
                    QueryString::OPTION_DISCOUNT_FACADE => $this->discountFacade,
                    QueryString::OPTION_QUERY_STRING_TYPE => SpecificationBuilder::TYPE_COLLECTOR,
                ]),
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
        return 'discount_calculator';
    }
}

