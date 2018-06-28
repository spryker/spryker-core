<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Communication\Form;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressLabelEntityTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyUnitAddressLabelChoiceFormType extends AbstractType
{
    const OPTION_VALUES_LABEL_CHOICES = 'company_unit_address_label_value_options';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addLabelSelectField($builder, $options);

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
        $resolver->setRequired(static::OPTION_VALUES_LABEL_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addLabelSelectField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            CompanyUnitAddressTransfer::LABEL_COLLECTION,
            Select2ComboBoxType::class,
            [
                'label' => 'Labels',
                'required' => false,
                'property_path' => CompanyUnitAddressTransfer::LABEL_COLLECTION . '.' . CompanyUnitAddressLabelCollectionTransfer::LABELS,
                'choices' => $options[static::OPTION_VALUES_LABEL_CHOICES],
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
    protected function addModelTransformer(FormBuilderInterface $builder)
    {
        $builder->get(CompanyUnitAddressTransfer::LABEL_COLLECTION)->addModelTransformer(
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

                    return new ArrayObject($labels);
                }
            )
        );
    }
}
