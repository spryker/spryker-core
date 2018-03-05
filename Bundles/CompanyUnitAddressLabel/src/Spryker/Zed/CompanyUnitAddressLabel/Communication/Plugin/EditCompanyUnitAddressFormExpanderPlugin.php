<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Communication\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddressGuiExtension\Communication\Plugin\EditCompanyUnitAddressFormExpanderPluginInterface;
use Spryker\Zed\CompanyUnitAddressLabel\Communication\Form\CompanyUnitAddressLabelChoiceFormType;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Communication\CompanyUnitAddressLabelCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface getFacade()
 */
class EditCompanyUnitAddressFormExpanderPlugin extends AbstractPlugin implements EditCompanyUnitAddressFormExpanderPluginInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        //TODO: set data from database to form
        $dataProvider = $this->getFactory()->createCompanyUnitAddressLabelChoiceFormDataProvider();

        $builder->add(
            CompanyUnitAddressTransfer::LABEL_COLLECTION,
            CompanyUnitAddressLabelChoiceFormType::class,
            $dataProvider->getOptions()
        );

        $builder->get(CompanyUnitAddressTransfer::LABEL_COLLECTION)
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($model) {
                    },
                    function ($labels) {
                        $collection = new CompanyUnitAddressLabelCollectionTransfer();

                        $collection->setLabels(new ArrayObject($labels['labels']));

                        return $collection;
                    }
                )
            );
    }
}
