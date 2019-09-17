<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Generator;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Spryker\Zed\ConfigurableBundle\Dependency\Service\ConfigurableBundleToUtilTextServiceInterface;

class ConfigurableBundleTemplateNameGenerator implements ConfigurableBundleTemplateNameGeneratorInterface
{
    protected const NAME_PREFIX = 'configurable_bundle.template.names';
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
        $slugifiedName = $this->utilTextService->generateSlug(
            $configurableBundleTemplateTransfer->getTranslations()[0]->getName()
        );

        $nameParts = [
            static::NAME_PREFIX,
            $slugifiedName,
            static::NAME_POSTFIX,
        ];

        return implode(static::PARTS_CONCATENATOR, $nameParts);
    }
}
