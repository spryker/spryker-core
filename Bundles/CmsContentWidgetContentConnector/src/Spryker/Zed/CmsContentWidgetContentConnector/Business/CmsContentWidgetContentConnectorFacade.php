<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsContentWidgetContentConnector\Business\CmsContentWidgetContentConnectorBusinessFactory getFactory()
 */
class CmsContentWidgetContentConnectorFacade extends AbstractFacade implements CmsContentWidgetContentConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @phpstan-return array<string, string>
     *
     * @param string[] $contentItemKeys
     *
     * @return string[]
     */
    public function mapContentItemKeys(array $contentItemKeys): array
    {
        return $this->getFactory()
            ->createCmsContentItemKeyMapper()
            ->mapContentItemKeys($contentItemKeys);
    }
}
