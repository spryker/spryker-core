<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Plugin\MerchantGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantUpdateFormViewExpanderPluginInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @method \Spryker\Zed\MerchantUserGui\Communication\MerchantUserGuiCommunicationFactory getFactory()
 */
class MerchantUserViewMerchantUpdateFormViewExpanderPlugin extends AbstractPlugin implements MerchantUpdateFormViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands FormView with data needed for the merchant user tab.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function expand(FormView $view, FormInterface $form, array $options): FormView
    {
        $view->vars['tables']['merchantUsersTable'] = $this->getFactory()->createMerchantUserTable(
            $form->getData()['idMerchant']
        )->render();

        return $view;
    }
}
