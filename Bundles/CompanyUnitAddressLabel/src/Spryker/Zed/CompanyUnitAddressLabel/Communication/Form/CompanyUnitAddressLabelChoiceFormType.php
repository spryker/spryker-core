<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Communication\Form;

use Generated\Shared\Transfer\SpyCompanyUnitAddressLabelEntityTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyUnitAddressLabelChoiceFormType extends AbstractType
{
    const FIELD_VALUES = 'company_unit_address_label_choice_field';
    const OPTION_VALUES_CHOICES = 'company_unit_address_label_value_options';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Communication\Form\CompanyUnitAddressLabelChoiceFormType
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_VALUES, Select2ComboBoxType::class, [
            'label' => 'Labels',
            'required' => false,
            //TODO: get data from collection
            'property_path' => '[labels]',
            'choices' => $options[static::OPTION_VALUES_CHOICES],
            'multiple' => true,
            //TODO: remove
            //'by_reference' => false,
        ]);

        $this->addModelTransformer($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setRequired(static::OPTION_VALUES_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addModelTransformer(FormBuilderInterface $builder)
    {
        //TODO: use these transformers only
        $builder->get(static::FIELD_VALUES)->addModelTransformer(
            new CallbackTransformer(
                function ($labels) {
                    if (empty($labels)) {
                        return $labels;
                    }
                    $result = [];
                    foreach ($labels as $label) {
                        $result[] = $label->getIdCompanyUnitAddressLabel();
                    }

                    return $result;
                },
                function ($data) {
                    $labels = [];
                    foreach ($data as $id) {
                        $labels[] = (new SpyCompanyUnitAddressLabelEntityTransfer())
                            ->setIdCompanyUnitAddressLabel($id);
                    }

                    return $labels;
                }
            )
        );
    }
}
