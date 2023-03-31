<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormViewExpanderPluginInterface;
use Symfony\Component\Form\FormView;

/**
 * @method \Spryker\Zed\CurrencyGui\Communication\CurrencyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CurrencyGui\CurrencyGuiConfig getConfig()
 */
class CurrencyStoreFormViewExpanderPlugin extends AbstractPlugin implements StoreFormViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds rendered currency tabs and tables as variables in template.
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
        $availableCurrenciesStoreRelationTabs = $this->getFactory()->createAvailableCurrencyRelationTabs();
        $assignedCurrenciesStoreRelationTabs = $this->getFactory()->createAssignedCurrencyRelationTabs();
        $availableCurrencyStoreTable = $this->getFactory()->createSelectableAvailableCurrencyStoreTable($storeTransfer->getIdStore());
        $assignedCurrencyStoreTable = $this->getFactory()->createSelectableAssignedCurrencyStoreTable($storeTransfer->getIdStore());

        $formView->vars['availableCurrencyRelationTabs'] = $availableCurrenciesStoreRelationTabs->createView();
        $formView->vars['assignedCurrencyRelationTabs'] = $assignedCurrenciesStoreRelationTabs->createView();
        $formView->vars['availableCurrencyTable'] = $availableCurrencyStoreTable->render();
        $formView->vars['assignedCurrencyTable'] = $assignedCurrencyStoreTable->render();

        return $formView;
    }
}
