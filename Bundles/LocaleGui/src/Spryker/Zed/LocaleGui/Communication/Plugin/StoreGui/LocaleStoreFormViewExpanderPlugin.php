<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormViewExpanderPluginInterface;
use Symfony\Component\Form\FormView;

/**
 * @method \Spryker\Zed\LocaleGui\Communication\LocaleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\LocaleGui\LocaleGuiConfig getConfig()
 */
class LocaleStoreFormViewExpanderPlugin extends AbstractPlugin implements StoreFormViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds rendered locale tabs and tables as variables in template.
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
        $availableLocalesStoreRelationTabs = $this->getFactory()->createAvailableLocaleRelationTabs();
        $assignedLocalesStoreRelationTabs = $this->getFactory()->createAssignedLocaleRelationTabs();
        $availableLocaleStoreTable = $this->getFactory()->createSelectableAvailableLocaleStoreTable($storeTransfer->getIdStore());
        $assignedLocaleStoreTable = $this->getFactory()->createSelectableAssignedLocaleStoreTable($storeTransfer->getIdStore());

        $formView->vars['availableLocaleRelationTabs'] = $availableLocalesStoreRelationTabs->createView();
        $formView->vars['assignedLocaleRelationTabs'] = $assignedLocalesStoreRelationTabs->createView();
        $formView->vars['availableLocaleTable'] = $availableLocaleStoreTable->render();
        $formView->vars['assignedLocaleTable'] = $assignedLocaleStoreTable->render();

        return $formView;
    }
}
