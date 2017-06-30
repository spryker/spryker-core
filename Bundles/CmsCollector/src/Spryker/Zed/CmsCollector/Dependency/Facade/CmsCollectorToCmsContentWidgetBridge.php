<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Dependency\Facade;

class CmsCollectorToCmsContentWidgetBridge implements CmsCollectorToCmsContentWidgetInterface
{

    /**
     * @var \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacadeInterface
     */
    protected $cmsFacade;

    /**
     * @param \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacadeInterface $cmsFacade
     */
    public function __construct($cmsFacade)
    {
        $this->cmsFacade = $cmsFacade;
    }

    /**
     * @param string $content
     *
     * @return array
     */
    public function mapContentWidgetParameters($content)
    {
        return $this->cmsFacade->mapContentWidgetParameters($content);
    }

}
