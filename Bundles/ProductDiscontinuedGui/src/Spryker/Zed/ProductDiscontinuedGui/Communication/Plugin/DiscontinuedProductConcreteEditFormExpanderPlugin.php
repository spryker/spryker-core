<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteEditFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinuedGui\Communication\ProductDiscontinuedGuiCommunicationFactory getFactory()
 */
class DiscontinuedProductConcreteEditFormExpanderPlugin extends AbstractPlugin implements ProductConcreteEditFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * Specification:
     * - Expands ProductConcreteEditForm with DiscontinueProductForm form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formType = $this->getFactory()->createDiscontinueProductForm();

        $formType->buildForm(
            $builder,
            [],
        );
    }
}
