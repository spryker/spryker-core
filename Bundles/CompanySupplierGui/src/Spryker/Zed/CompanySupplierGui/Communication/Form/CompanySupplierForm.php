<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication\Form;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyCompanyEntityTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanySupplierForm extends AbstractType
{
    public const OPTION_VALUES_COMPANY_SUPPLIER = 'OPTION_VALUES_COMPANY_SUPPLIER';
    protected const LABEL_COMPANY_SUPPLIER = 'Suppliers';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Spryker\Zed\CompanySupplierGui\Communication\Form\CompanySupplierForm
     */
    public function buildForm(FormBuilderInterface $builder, array $options): CompanySupplierForm
    {
        $this->addCompanySuppliersSelectField($builder, $options);

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
        $resolver->setRequired(static::OPTION_VALUES_COMPANY_SUPPLIER);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addCompanySuppliersSelectField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            ProductConcreteTransfer::COMPANY_SUPPLIERS,
            Select2ComboBoxType::class,
            [
                'label' => static::LABEL_COMPANY_SUPPLIER,
                'required' => false,
                'choices' => $options[static::OPTION_VALUES_COMPANY_SUPPLIER],
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
        $builder->get(ProductConcreteTransfer::COMPANY_SUPPLIERS)->addModelTransformer(
            new CallbackTransformer(
                function ($suppliers) {
                    if (empty($suppliers)) {
                        return $suppliers;
                    }
                    $result = [];
                    /** @var \Generated\Shared\Transfer\SpyCompanyEntityTransfer $supplier */
                    foreach ($suppliers as $supplier) {
                        $result[] = $supplier->getIdCompany();
                    }

                    return $result;
                },
                function ($data) {
                    $suppliers = [];
                    foreach ($data as $id) {
                        $suppliers[] = (new SpyCompanyEntityTransfer())
                            ->setIdCompany($id);
                    }

                    return new ArrayObject($suppliers);
                }
            )
        );
    }
}
