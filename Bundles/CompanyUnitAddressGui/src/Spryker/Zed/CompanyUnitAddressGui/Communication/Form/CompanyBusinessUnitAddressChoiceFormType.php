<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Form;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyBusinessUnitAddressChoiceFormType extends AbstractType
{
    public const OPTION_VALUES_ADDRESSES_CHOICES = 'company_business_unit_address_value_options';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function buildForm(FormBuilderInterface $builder, array $options): self
    {
        $this->addLabelSelectField($builder, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setRequired(static::OPTION_VALUES_ADDRESSES_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addLabelSelectField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION,
            Select2ComboBoxType::class,
            [
                'label' => 'Addresses',
                'required' => false,
                'property_path' => CompanyBusinessUnitTransfer::ADDRESS_COLLECTION . '.' . CompanyUnitAddressCollectionTransfer::COMPANY_UNIT_ADDRESSES,
                'choices' => array_flip($options[static::OPTION_VALUES_ADDRESSES_CHOICES]),
                'multiple' => true,
            ]
        );

        $this->addModelTransformer($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addModelTransformer(FormBuilderInterface $builder): void
    {
        $builder->get(CompanyBusinessUnitTransfer::ADDRESS_COLLECTION)->addModelTransformer(
            new CallbackTransformer(
                function ($addresses) {
                    if (empty($addresses)) {
                        return $addresses;
                    }
                    $result = [];
                    foreach ($addresses as $address) {
                        $result[] = $address->getIdCompanyUnitAddress();
                    }

                    return $result;
                },
                function ($data) {
                    $labels = [];
                    foreach ($data as $id) {
                        $labels[] = (new SpyCompanyUnitAddressEntityTransfer())
                            ->setIdCompanyUnitAddress($id);
                    }

                    return new ArrayObject($labels);
                }
            )
        );
    }
}
