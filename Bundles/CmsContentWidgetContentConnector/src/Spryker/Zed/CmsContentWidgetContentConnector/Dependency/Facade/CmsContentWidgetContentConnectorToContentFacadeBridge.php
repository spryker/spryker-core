<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentConnector\Dependency\Facade;

class CmsContentWidgetContentConnectorToContentFacadeBridge implements CmsContentWidgetContentConnectorToContentFacadeInterface
{
    /**
     * @var \Spryker\Zed\Content\Business\ContentFacadeInterface
     */
    protected $contentFacade;

    /**
     * @param \Spryker\Zed\Content\Business\ContentFacadeInterface $contentFacade
     */
    public function __construct($contentFacade)
    {
        $this->contentFacade = $contentFacade;
    }

    /**
     * @param string[] $contentKeys
     *
     * @return \Generated\Shared\Transfer\ContentTransfer[]
     */
    public function getContentByKeys(array $contentKeys): array
    {
        return $this->contentFacade->getContentByKeys($contentKeys);
    }
}
