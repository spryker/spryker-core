<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\CompanyGui\Communication\CompanyGuiCommunicationFactory getFactory()
 */
class CompanyBusinessUnitForm extends AbstractType
{
    const FIELD_ID_COMPANY_BUSINESS_UNIT = 'id_company_business_unit';
    const FIELD_NAME = 'name';
    const FIELD_IBAN = 'iban';
    const FIELD_BIC = 'bic';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'company-business-unit';
    }


    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdCompanyField($builder)
            ->addNameField($builder)
            ->addIbanField($builder)
            ->addBicField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitForm
     */
    protected function addIdCompanyField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_COMPANY_BUSINESS_UNIT, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitForm
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_NAME, TextType::class, [
            'label' => 'Name',
            'constraints' => $this->getTextFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitForm
     */
    protected function addIbanField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_IBAN, TextType::class, [
            'label' => 'IBAN',
            'constraints' => $this->getTextFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitForm
     */
    protected function addBicField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_BIC, TextType::class, [
            'label' => 'BIC',
            'constraints' => $this->getTextFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function getTextFieldConstraints()
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 100]),
        ];
    }
}
