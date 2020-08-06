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
     * - Returns true if the plugin will provide the condition editor for a given CmsSlotTemplateConfigurationTransfer criteria.
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
     * - Add form parts to the main form builder.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function addConditionForm(FormBuilderInterface $builder): void;
}
