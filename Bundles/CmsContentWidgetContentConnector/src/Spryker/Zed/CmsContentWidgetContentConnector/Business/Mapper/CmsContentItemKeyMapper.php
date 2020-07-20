<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentConnector\Business\Mapper;

use Spryker\Zed\CmsContentWidgetContentConnector\Dependency\Facade\CmsContentWidgetContentConnectorToContentFacadeInterface;

class CmsContentItemKeyMapper implements CmsContentItemKeyMapperInterface
{
    /**
     * @var \Spryker\Zed\CmsContentWidgetContentConnector\Dependency\Facade\CmsContentWidgetContentConnectorToContentFacadeInterface
     */
    protected $contentFacade;

    /**
     * @param \Spryker\Zed\CmsContentWidgetContentConnector\Dependency\Facade\CmsContentWidgetContentConnectorToContentFacadeInterface $contentFacade
     */
    public function __construct(CmsContentWidgetContentConnectorToContentFacadeInterface $contentFacade)
    {
        $this->contentFacade = $contentFacade;
    }

    /**
     * @phpstan-return array<string, string>
     *
     * @param string[] $contentItemKeys
     *
     * @return string[]
     */
    public function mapContentItemKeys(array $contentItemKeys): array
    {
        $contentTransferCollection = $this->contentFacade->getContentByKeys($contentItemKeys);

        $contentItemKeys = [];
        foreach ($contentTransferCollection as $contentTransfer) {
            $contentItemKeys[$contentTransfer->getKey()] = $contentTransfer->getKey();
        }

        return $contentItemKeys;
    }
}
