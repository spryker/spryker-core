<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Generator;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;

class ConfigurableBundleTemplateNameGenerator implements ConfigurableBundleTemplateNameGeneratorInterface
{
    protected const NAME_PREFIX = 'configurable_bundle.template';
    protected const NAME_POSTFIX = 'name';
    protected const SPACE_REPLACEMENT = '_';
    protected const PARTS_CONCATENATOR = '.';

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function setConfigurableBundleTemplateName(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateTransfer->requireTranslations();

        $configurableBundleTemplateTransfer->setName(
            $this->generateName($configurableBundleTemplateTransfer)
        );

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return string
     */
    protected function generateName(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): string
    {
        $nameParts = [
            static::NAME_PREFIX,
            $configurableBundleTemplateTransfer->getTranslations()[0]->getName(),
            static::NAME_POSTFIX,
        ];

        $name = implode(static::PARTS_CONCATENATOR, $nameParts);
        $name = preg_replace('/[^ \w-_.]/', '', $name);
        $name = preg_replace('!\s+!', static::SPACE_REPLACEMENT, $name);

        return strtolower($name);
    }
}
