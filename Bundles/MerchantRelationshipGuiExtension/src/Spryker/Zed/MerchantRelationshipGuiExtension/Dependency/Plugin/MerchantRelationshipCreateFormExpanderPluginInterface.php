<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGuiExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

interface MerchantRelationshipCreateFormExpanderPluginInterface
{
    /**
     * Specification:
     * - Provides functionality to extend Merchant Relationship create form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void;
}
