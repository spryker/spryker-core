<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormViewExpanderPluginInterface;
use Symfony\Component\Form\FormView;

/**
 * @method \Spryker\Zed\CountryGui\Communication\CountryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CountryGui\CountryGuiConfig getConfig()
 */
class CountryStoreFormViewExpanderPlugin extends AbstractPlugin implements StoreFormViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds rendered country tabs and tables as variables in template.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormView $formView
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function expandTemplateVariables(FormView $formView, StoreTransfer $storeTransfer): FormView
    {
        $availableCountriesStoreRelationTabs = $this->getFactory()->createAvailableCountryRelationTabs();
        $assignedCountriesStoreRelationTabs = $this->getFactory()->createAssignedCountryRelationTabs();
        $availableCountryStoreTable = $this->getFactory()->createSelectableAvailableCountryStoreTable($storeTransfer->getIdStore());
        $assignedCountryStoreTable = $this->getFactory()->createSelectableAssignedCountryStoreTable($storeTransfer->getIdStore());

        $formView->vars['availableCountryRelationTabs'] = $availableCountriesStoreRelationTabs->createView();
        $formView->vars['assignedCountryRelationTabs'] = $assignedCountriesStoreRelationTabs->createView();
        $formView->vars['availableCountryTable'] = $availableCountryStoreTable->render();
        $formView->vars['assignedCountryTable'] = $assignedCountryStoreTable->render();

        return $formView;
    }
}
