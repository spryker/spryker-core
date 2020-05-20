<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Client;

interface CmsContentWidgetContentItemConnectorToContentStorageClientInterface
{
    /**
     * @param string[] $contentKeys
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer[]
     */
    public function getContentTypeContextByKeys(array $contentKeys, string $localeName): array;
}
