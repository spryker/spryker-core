<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentConnector\Dependency\Facade;

interface CmsContentWidgetContentConnectorToContentFacadeInterface
{
    /**
     * @param string[] $contentKeys
     *
     * @return \Generated\Shared\Transfer\ContentTransfer[]
     */
    public function getContentByKeys(array $contentKeys): array;
}
