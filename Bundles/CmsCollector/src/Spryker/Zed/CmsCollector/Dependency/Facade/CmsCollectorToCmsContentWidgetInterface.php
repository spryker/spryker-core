<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Dependency\Facade;

interface CmsCollectorToCmsContentWidgetInterface
{

    /**
     * @param string $content
     *
     * @return array
     */
    public function mapContentWidgetParameters($content);

}
