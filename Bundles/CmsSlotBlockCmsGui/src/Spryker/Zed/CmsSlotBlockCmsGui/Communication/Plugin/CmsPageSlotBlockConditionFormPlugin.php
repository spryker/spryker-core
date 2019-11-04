<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCmsGui\Communication\Plugin;

use Generated\Shared\Transfer\CmsSlotTemplateConfigurationTransfer;
use Spryker\Zed\CmsSlotBlockGuiExtension\Communication\Plugin\CmsSlotBlockGuiConditionFormPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CmsSlotBlockCmsGui\Communication\CmsSlotBlockCmsGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsSlotBlockCmsGui\CmsSlotBlockCmsGuiConfig getConfig()
 */
class CmsPageSlotBlockConditionFormPlugin extends AbstractPlugin implements CmsSlotBlockGuiConditionFormPluginInterface
{
    /**
     * @uses \Spryker\Shared\CmsSlotBlockCmsConnector\CmsSlotBlockCmsConnectorConfig::CONDITION_KEY
     */
    protected const CONDITION_KEY = 'cms_page';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotTemplateConfigurationTransfer $cmsSlotTemplateConfigurationTransfer
     *
     * @return bool
     */
    public function isApplicable(CmsSlotTemplateConfigurationTransfer $cmsSlotTemplateConfigurationTransfer): bool
    {
        return in_array(static::CONDITION_KEY, $cmsSlotTemplateConfigurationTransfer->getConditions(), true);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function addConditionForm(FormBuilderInterface $builder): void
    {
        $cmsPageSlotBlockConditionForm = $this->getFactory()->createCmsPageConditionForm();
        $cmsPageSlotBlockFormDataProvider = $this->getFactory()->createCmsPageConditionFormDataProvider();
        $cmsPageSlotBlockConditionForm->buildForm($builder, $cmsPageSlotBlockFormDataProvider->getOptions());
    }
}
