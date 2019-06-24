<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication\Mapper;

use Generated\Shared\Transfer\ContentWidgetTemplateTransfer;
use Spryker\Zed\ContentFileGui\ContentFileGuiConfig;

class ContentFileGuiEditorConfigurationMapper implements ContentFileGuiEditorConfigurationMapperInterface
{
    /**
     * @var \Spryker\Zed\ContentFileGui\ContentFileGuiConfig
     */
    protected $contentFileGuiConfig;

    /**
     * @param \Spryker\Zed\ContentFileGui\ContentFileGuiConfig $contentFileGuiConfig
     */
    public function __construct(ContentFileGuiConfig $contentFileGuiConfig)
    {
        $this->contentFileGuiConfig = $contentFileGuiConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    public function getTemplates(): array
    {
        $templates = [];

        foreach ($this->contentFileGuiConfig->getContentWidgetTemplates() as $templateIdentifier => $templateName) {
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
        return '{{ ' . $this->contentFileGuiConfig->getTwigFunctionName() . "('%KEY%', '%TEMPLATE%') }}";
    }
}
