<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Form;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessOnBehalfGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiConfig getConfig()
 */
class CustomerBusinessUnitAttachForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_FK_COMPANY_BUSINESS_UNIT = 'fk_company_business_unit';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'customer_business_unit_attach';
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
            'data_class' => CompanyUserTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCompanyBusinessUnitCollectionField($builder);
        $this->executeCustomerBusinessUnitAttachFormExpanderPlugins($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCompanyBusinessUnitCollectionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_COMPANY_BUSINESS_UNIT, ChoiceType::class, [
            'label' => 'Business Unit',
            'placeholder' => 'Business Unit name',
            'choice_loader' => new CallbackChoiceLoader(function () use ($builder) {
                return $this->getFactory()
                    ->createCustomerCompanyAttachFormDataProvider()
                    ->getCompanyBusinessUnitChoices($builder->getData()->getFkCompany());
            }),
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function executeCustomerBusinessUnitAttachFormExpanderPlugins(FormBuilderInterface $builder)
    {
        foreach ($this->getFactory()->getCustomerBusinessUnitAttachFormExpanderPlugins() as $customerBusinessUnitAttachFormExpanderPlugin) {
            $builder = $customerBusinessUnitAttachFormExpanderPlugin->expand($builder);
        }

        return $this;
    }
}
