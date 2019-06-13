<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Mapper\ContentGui;

use Generated\Shared\Transfer\ContentWidgetTemplateTransfer;
use Spryker\Zed\ContentProductGui\ContentProductGuiConfig;

class ContentProductContentGuiEditorConfigurationMapper implements ContentProductContentGuiEditorConfigurationMapperInterface
{
    /**
     * @var \Spryker\Zed\ContentProductGui\ContentProductGuiConfig
     */
    protected $contentProductGuiConfig;

    /**
     * @param \Spryker\Zed\ContentProductGui\ContentProductGuiConfig $contentProductGuiConfig
     */
    public function __construct(ContentProductGuiConfig $contentProductGuiConfig)
    {
        $this->contentProductGuiConfig = $contentProductGuiConfig;
    }

    /**
     * @return array
     */
    public function getTemplates(): array
    {
        $templates = [];

        foreach ($this->contentProductGuiConfig->getContentWidgetTemplates() as $templateIdentifier => $templateName) {
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
        return "{{ " . $this->contentProductGuiConfig->getTwigFunctionName() . "('%KEY%', '%TEMPLATE%') }}";
    }
}
