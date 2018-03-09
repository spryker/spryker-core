<?php


namespace Spryker\Zed\CompanySupplierGui\Communication;


use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CompanySupplierForm extends AbstractType
{
    protected const FIELD_COMPANY_SUPPLIER = 'company_supplier';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $this;
    }

    protected function addCompanySuppliersSelectField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_COMPANY_SUPPLIER,
            SelectType::class,
            [
                'label' => 'company supplier',
                'required' => false,
                'choices' => [1,2,3],
                'multiple' => true
            ]
        );
    }
}