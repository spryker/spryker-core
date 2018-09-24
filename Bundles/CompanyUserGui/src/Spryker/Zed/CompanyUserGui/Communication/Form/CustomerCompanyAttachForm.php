<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class CustomerCompanyAttachForm extends AbstractType
{
    public const FIELD_COMPANY = 'fkCompany';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCompany($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCompany(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_COMPANY, ChoiceType::class, [
            'expanded' => false,
            'multiple' => false,
            'label' => 'Company',
            'choices' => array_flip($this->getFactory()->createCustomerCompanyAttachFormDataProvider()->getOptions()[self::FIELD_COMPANY]),
            'choices_as_values' => true,
            'constraints' => [
                new GreaterThan([
                    'value' => 0,
                    'message' => 'Select company.',
                ]),
            ],
            'attr' => [],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'company_user_to_company';
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
