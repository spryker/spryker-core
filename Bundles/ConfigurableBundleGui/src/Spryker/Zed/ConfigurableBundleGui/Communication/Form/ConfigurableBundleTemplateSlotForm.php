<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Form;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleGui\Communication\ConfigurableBundleGuiCommunicationFactory getFactory()
 */
class ConfigurableBundleTemplateSlotForm extends AbstractConfigurableBundleForm
{
    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return [
            static::OPTION_DATA_CLASS => ConfigurableBundleTemplateSlotTransfer::class,
        ];
    }

    /**
     * @return string
     */
    protected function getTranslationFormClass(): string
    {
        return ConfigurableBundleTemplateSlotTranslationForm::class;
    }
}
