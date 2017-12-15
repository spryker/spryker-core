<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Form;

use ArrayObject;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 * @method \Spryker\Zed\Tax\Communication\TaxCommunicationFactory getFactory()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 */
class TaxSetForm extends AbstractType
{
    const FIELD_NAME = 'name';
    const FIELD_TAX_RATES = 'taxRates';
    const FIELD_ID_TAX_SET = 'idTaxSet';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addName($builder)
            ->addTaxRates($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addName(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_NAME,
            TextType::class,
            [
                'label' => 'Name',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
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
    protected function addTaxRates(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_TAX_RATES, ChoiceType::class, [
            'expanded' => true,
            'multiple' => true,
            'label' => 'Tax rates',
            'choices' => $this->getFactory()->createTaxSetFormDataProvider()->getOptions()[self::FIELD_TAX_RATES],
            'constraints' => [
                new Callback([
                    'callback' => function (ArrayObject $taxRates, ExecutionContextInterface $context) {
                        if ($taxRates->count() <= 0) {
                            $context->addViolation('You should choose one or more tax rates');
                        }
                    },
                ]),
            ],
        ]);

        $builder->get(self::FIELD_TAX_RATES)
            ->addModelTransformer(new CallbackTransformer(
                function ($taxRates) {
                    if ($taxRates) {
                        return (array)$taxRates;
                    }
                },
                function ($taxRates) {
                    return new ArrayObject($taxRates);
                }
            ));

        return $this;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'tax_set';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
