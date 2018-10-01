<?php
/**
 * Created by PhpStorm.
 * User: ruslan.ivanov
 * Date: 10/1/18
 * Time: 3:54 PM
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin;


use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CustomerCompanyAttachFormExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 */
class BusinessUnitFieldCustomerCompanyAttachFormExpander extends AbstractPlugin implements CustomerCompanyAttachFormExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder): FormBuilderInterface
    {
        $formType = $this->getFactory()
            ->createCompanyUserBusinessUnitChoiceFormType();

        $dataProvider = $this->getFactory()
            ->createCompanyUserBusinessUnitChoiceFormDataProvider();

        $companyUserTransfer = $builder->getData();
        $dataProvider->getData($companyUserTransfer);

        $formType->buildForm(
            $builder,
            $dataProvider->getOptions()
        );

        return $builder;
    }
}