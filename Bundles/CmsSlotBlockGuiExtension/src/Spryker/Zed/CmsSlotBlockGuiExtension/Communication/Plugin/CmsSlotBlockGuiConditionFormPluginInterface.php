<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGuiExtension\Communication\Plugin;

use Generated\Shared\Transfer\CmsSlotTemplateConfigurationTransfer;
use Symfony\Component\Form\FormBuilderInterface;

interface CmsSlotBlockGuiConditionFormPluginInterface
{
    /**
     * Specification:
     * - Returns true if provided conditions applicable for using this plugin.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotTemplateConfigurationTransfer $cmsSlotTemplateConfigurationTransfer
     *
     * @return bool
     */
    public function isApplicable(CmsSlotTemplateConfigurationTransfer $cmsSlotTemplateConfigurationTransfer): bool;

    /**
     * Specification:
     * - Adds additional form with conditions.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function addConditionForm(FormBuilderInterface $builder): void;
}
