<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\CompanyUnitAddressGui\Communication\CompanyUnitAddressGuiCommunicationFactory getFactory()
 */
class CompanyUnitAddressForm extends AbstractType
{
    const FIELD_ID_COMPANY_UNIT_ADDRESS = 'id_company_unit_address';
    const FIELD_ADDRESS_1 = 'address1';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addIdCompanyUnitAddressField($builder)
            ->addAddressField($builder)
            ->addPluginForms($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCompanyUnitAddressField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_COMPANY_UNIT_ADDRESS, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddressField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ADDRESS_1, TextType::class, [
            'label' => 'Address',
            'disabled' => true,
            'constraints' => [
                new Required(),
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPluginForms(FormBuilderInterface $builder)
    {
        foreach ($this->getFactory()->getCompanyUnitAddressFormPlugins() as $formPlugin) {
            $formPlugin->buildForm($builder);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'company_unit_address';
    }
}
