<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentItemConnector\Business\Mapper;

use Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Facade\CmsContentWidgetContentItemConnectorToContentFacadeInterface;

class CmsContentItemKeyMapper implements CmsContentItemKeyMapperInterface
{
    /**
     * @var \Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Facade\CmsContentWidgetContentItemConnectorToContentFacadeInterface
     */
    protected $contentFacade;

    /**
     * @param \Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Facade\CmsContentWidgetContentItemConnectorToContentFacadeInterface $contentFacade
     */
    public function __construct(
        CmsContentWidgetContentItemConnectorToContentFacadeInterface $contentFacade
    ) {
        $this->contentFacade = $contentFacade;
    }

    /**
     * @param string[] $keyList
     *
     * @return string[]
     */
    public function mapContentItemKeyList(array $keyList): array
    {
        $contentTransferCollection = $this->contentFacade->getContentByKeys($keyList);

        $contentItemKeys = [];
        foreach ($contentTransferCollection as $contentTransfer) {
            $contentItemKeys[] = $contentTransfer->getKey();
        }

        return $contentItemKeys;
    }
}
