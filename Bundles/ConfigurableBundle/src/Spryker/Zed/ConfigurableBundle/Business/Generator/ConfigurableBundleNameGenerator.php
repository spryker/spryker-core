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
    protected const PREFIX_TEMPLATE_NAME = 'configurable_bundle.templates';
    protected const PREFIX_TEMPLATE_SLOT_NAME = 'configurable_bundle.template_slots';
    protected const POSTFIX_NAME = 'name';
    protected const CONCATENATOR = '.';

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
     * @return string
     */
    public function generateTemplateName(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): string
    {
        $configurableBundleTemplateTransfer->requireTranslations();

        return $this->generateName(
            $configurableBundleTemplateTransfer->getTranslations()[0]->getName(),
            static::PREFIX_TEMPLATE_NAME
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return string
     */
    public function generateTemplateSlotName(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): string
    {
        $configurableBundleTemplateSlotTransfer->requireTranslations();

        return $this->generateName(
            $configurableBundleTemplateSlotTransfer->getTranslations()[0]->getName(),
            static::PREFIX_TEMPLATE_SLOT_NAME
        );
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
            static::POSTFIX_NAME,
        ];

        return implode(static::CONCATENATOR, $nameParts);
    }
}
