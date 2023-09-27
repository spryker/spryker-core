<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Plugin\User;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\WarehouseUserGui\Communication\WarehouseUserGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\WarehouseUserGui\WarehouseUserGuiConfig getConfig()
 */
class WarehouseUserAssignmentUserFormExpanderPlugin extends AbstractPlugin implements UserFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the form with the `is_warehouse_user` checkbox.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder): void
    {
        $this->getFactory()->createWarehouseUserAssignmentFormExpander()->expandForm($builder);
    }
}
