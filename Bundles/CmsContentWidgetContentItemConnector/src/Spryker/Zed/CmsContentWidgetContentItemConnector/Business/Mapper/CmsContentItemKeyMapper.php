<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentItemConnector\Business\Mapper;

use Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Client\CmsContentWidgetContentItemConnectorToContentStorageClientInterface;

class CmsContentItemKeyMapper implements CmsContentItemKeyMapperInterface
{
    /**
     * @var \Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Client\CmsContentWidgetContentItemConnectorToContentStorageClientInterface
     */
    protected $contentStorageClient;

    /**
     * @param \Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Client\CmsContentWidgetContentItemConnectorToContentStorageClientInterface $contentStorageClient
     */
    public function __construct(CmsContentWidgetContentItemConnectorToContentStorageClientInterface $contentStorageClient)
    {
        $this->contentStorageClient = $contentStorageClient;
    }

    /**
     * @param string[] $keyList
     *
     * @return array
     */
    public function mapContentItemKeyList(array $keyList): array
    {
        // TODO get content Items by keys and filter them
        return $keyList;
    }
}
