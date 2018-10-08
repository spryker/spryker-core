<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

/**
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 * @method \Spryker\Zed\Tax\Communication\TaxCommunicationFactory getFactory()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 */
class TaxRateForm extends AbstractType
{
    public const FIELD_NAME = 'name';
    public const FIELD_RATE = 'rate';
    public const FIELD_COUNTRY = 'fkCountry';
    public const FIELD_ID_TAX_RATE = 'idTaxRate';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addName($builder)
            ->addCountry($builder)
            ->addPercentage($builder);
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
    protected function addCountry(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_COUNTRY, ChoiceType::class, [
            'expanded' => false,
            'multiple' => false,
            'label' => 'Country',
            'choices' => array_flip($this->getFactory()->createTaxRateFormDataProvider()->getOptions()[self::FIELD_COUNTRY]),
            'choices_as_values' => true,
            'constraints' => [
                new GreaterThan([
                    'value' => 0,
                    'message' => 'Select country.',
                ]),
            ],
            'attr' => [],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPercentage(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_RATE,
            TextType::class,
            [
                'label' => 'Percentage',
                'required' => true,
                'constraints' => [
                    new Range([
                        'min' => 0,
                        'max' => 100,
                    ]),
                ],
            ]
        );

        $builder->get(self::FIELD_RATE)
            ->addModelTransformer($this->getFactory()->createPercentageTransformer());

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'tax_rate';
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
