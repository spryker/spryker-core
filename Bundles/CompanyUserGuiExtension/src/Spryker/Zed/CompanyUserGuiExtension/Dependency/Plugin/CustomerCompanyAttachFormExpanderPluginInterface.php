<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

interface CustomerCompanyAttachFormExpanderPluginInterface
{
    /**
     * Specification:
     * - Adds form parts to the CustomerCompanyAttachForm form builder
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder): void;
}
