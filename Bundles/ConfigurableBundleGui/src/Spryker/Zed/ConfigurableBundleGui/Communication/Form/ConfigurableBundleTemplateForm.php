<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Form;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleGui\Communication\ConfigurableBundleGuiCommunicationFactory getFactory()
 */
class ConfigurableBundleTemplateForm extends AbstractConfigurableBundleForm
{
    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return [
            static::OPTION_DATA_CLASS => ConfigurableBundleTemplateTransfer::class,
        ];
    }

    /**
     * @return string
     */
    protected function getTranslationFormClass(): string
    {
        return ConfigurableBundleTemplateTranslationForm::class;
    }
}
