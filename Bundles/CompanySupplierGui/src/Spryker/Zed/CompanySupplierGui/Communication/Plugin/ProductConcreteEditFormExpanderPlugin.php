<?php

namespace Spryker\Zed\CompanySupplierGui\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanySupplierGui\Communication\CompanySupplierGuiCommunicationFactory getFactory()
 */
class ProductConcreteEditFormExpanderPlugin extends AbstractPlugin implements ProductConcreteEditFormExpanderPluginInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this->getFactory()
                       ->createCompanySupplierForm();

        $dataProvider = $this->getFactory()->createCompanySupplierFormDataProvider();

       $formType->buildForm(
           $builder,
           $dataProvider->getOptions()
       );
    }
}