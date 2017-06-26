<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\ContentWidget;

interface ContentWidgetFunctionMatcherInterface
{

    /**
     * @param string $content
     *
     * @return \Generated\Shared\Transfer\CmsContentWidgetFunctionsTransfer
     */
    public function extractTwigFunctions($content);

}
