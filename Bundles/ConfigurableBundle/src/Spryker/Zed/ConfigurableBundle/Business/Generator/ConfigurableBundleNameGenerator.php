<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Generator;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Spryker\Zed\ConfigurableBundle\Dependency\Service\ConfigurableBundleToUtilTextServiceInterface;

class ConfigurableBundleNameGenerator implements ConfigurableBundleNameGeneratorInterface
{
    protected const TENPLATE_NAME_PREFIX = 'configurable_bundle.templates';
    protected const SLOT_NAME_PREFIX = 'configurable_bundle.template_slots';
    protected const NAME_POSTFIX = 'name';
    protected const SPACE_REPLACEMENT = '_';
    protected const PARTS_CONCATENATOR = '.';

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Service\ConfigurableBundleToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Service\ConfigurableBundleToUtilTextServiceInterface $utilTextService
     */
    public function __construct(ConfigurableBundleToUtilTextServiceInterface $utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

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
            $this->generateName($configurableBundleTemplateTransfer->getTranslations()[0]->getName(), static::TENPLATE_NAME_PREFIX)
        );

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function setConfigurableBundleTemplateSlotName(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotTransfer->requireTranslations();

        $configurableBundleTemplateSlotTransfer->setName(
            $this->generateName($configurableBundleTemplateSlotTransfer->getTranslations()[0]->getName(), static::SLOT_NAME_PREFIX)
        );

        return $configurableBundleTemplateSlotTransfer;
    }

    /**
     * @param string $translation
     * @param string $prefix
     *
     * @return string
     */
    protected function generateName(string $translation, string $prefix): string
    {
        $slugifiedName = $this->utilTextService->generateSlug($translation);

        $nameParts = [
            $prefix,
            $slugifiedName,
            static::NAME_POSTFIX,
        ];

        return implode(static::PARTS_CONCATENATOR, $nameParts);
    }
}
