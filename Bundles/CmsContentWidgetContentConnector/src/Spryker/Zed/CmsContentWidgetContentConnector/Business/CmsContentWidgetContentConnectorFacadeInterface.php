<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentConnector\Business;

interface CmsContentWidgetContentConnectorFacadeInterface
{
    /**
     * Specification:
     * - Check given content item keys for existence.
     * - Maps given content item keys to corresponding persistent keys.
     *
     * @api
     *
     * @param array<string> $contentItemKeys
     *
     * @return array<string, string>
     */
    public function mapContentItemKeys(array $contentItemKeys): array;
}
