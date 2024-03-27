<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGuiExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Implement this plugin to expand the online profile section of the merchant profile form with additional fields.
 */
interface OnlineProfileMerchantProfileFormExpanderPluginInterface
{
    /**
     * Specification:
     * - Adds form fields to the online profile section of the merchant profile form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void;
}
