<?php


namespace Spryker\Zed\CompanySupplierGui\Communication\Form;


use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyCompanyEntityTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanySupplierForm extends AbstractType
{
    public const OPTION_VALUES_COMPANY_SUPPLIER = 'OPTION_VALUES_COMPANY_SUPPLIER';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCompanySuppliersSelectField($builder, $options);

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
       $resolver->setRequired(static::OPTION_VALUES_COMPANY_SUPPLIER);
   }

    protected function addCompanySuppliersSelectField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            ProductConcreteTransfer::COMPANY_SUPPLIERS,
            SelectType::class,
            [
                'label' => 'company supplier',
                'required' => false,
                'choices' => $options[static::OPTION_VALUES_COMPANY_SUPPLIER],
                'multiple' => true,
//                'property_path' => '['.ProductConcreteTransfer::COMPANY_SUPPLIERS . '][' . CompanySupplierCollectionTransfer::SUPPLIERS.']',
                'property_path' => '['.CompanySupplierCollectionTransfer::SUPPLIERS.']',
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
       $builder->get(ProductConcreteTransfer::COMPANY_SUPPLIERS)->addModelTransformer(
           new CallbackTransformer(
               function ($suppliers) {
                       if (empty($suppliers)) {
                               return $suppliers;
                   }
                   $result = [];
                       /** @var SpyCompanyEntityTransfer $supplier */
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

                   return new \ArrayObject($suppliers);
               }
           )
       );
   }
}