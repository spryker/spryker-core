<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Communication\Mapper;

use Generated\Shared\Transfer\ContentWidgetTemplateTransfer;
use Spryker\Zed\ContentNavigationGui\ContentNavigationGuiConfig;

class ContentNavigationContentGuiEditorConfigurationMapper implements ContentNavigationContentGuiEditorConfigurationMapperInterface
{
    /**
     * @var \Spryker\Zed\ContentNavigationGui\ContentNavigationGuiConfig
     */
    protected $contentNavigationGuiConfig;

    /**
     * @param \Spryker\Zed\ContentNavigationGui\ContentNavigationGuiConfig $contentNavigationGuiConfig
     */
    public function __construct(ContentNavigationGuiConfig $contentNavigationGuiConfig)
    {
        $this->contentNavigationGuiConfig = $contentNavigationGuiConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    public function getTemplates(): array
    {
        $templates = [];

        foreach ($this->contentNavigationGuiConfig->getContentWidgetTemplates() as $templateIdentifier => $templateName) {
            $templates[] = (new ContentWidgetTemplateTransfer())
                ->setIdentifier($templateIdentifier)
                ->setName($templateName);
        }

        return $templates;
    }

    /**
     * @return string
     */
    public function getTwigFunctionTemplate(): string
    {
        return '{{ ' . $this->contentNavigationGuiConfig->getTwigFunctionName() . "('%KEY%', '%TEMPLATE%') }}";
    }
}
