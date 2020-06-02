<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Facade;

interface CmsContentWidgetContentItemConnectorToContentFacadeInterface
{
    /**
     * @phpstan-param array<string> $contentKeys
     *
     * @phpstan-return array<int, \Generated\Shared\Transfer\ContentTransfer>*
     *
     * @param string[] $contentKeys
     *
     * @return \Generated\Shared\Transfer\ContentTransfer[]
     */
    public function getContentByKeys(array $contentKeys): array;
}
