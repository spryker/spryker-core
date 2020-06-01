<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentItemConnector\Business;

interface CmsContentWidgetContentItemConnectorFacadeInterface
{
    /**
     * Specification:
     *  - Check given content item key for existence.
     *
     * @api
     *
     * @param string[] $keyList
     *
     * @return string[]
     */
    public function mapContentItemKeyList(array $keyList): array;
}
