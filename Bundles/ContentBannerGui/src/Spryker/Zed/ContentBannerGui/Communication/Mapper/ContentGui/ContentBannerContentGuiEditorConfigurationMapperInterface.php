<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui;

interface ContentBannerContentGuiEditorConfigurationMapperInterface
{
    /**
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    public function getTemplates(): array;

    /**
     * @return string
     */
    public function getTwigFunctionTemplate(): string;
}
