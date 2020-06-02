<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentItemConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsContentWidgetContentItemConnector\Business\CmsContentWidgetContentItemConnectorBusinessFactory getFactory()
 */
class CmsContentWidgetContentItemConnectorFacade extends AbstractFacade implements CmsContentWidgetContentItemConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @phpstan-return array<string, string>
     *
     * @param string[] $keyList
     *
     * @return string[]
     */
    public function mapContentItemKeyList(array $keyList): array
    {
        return $this->getFactory()
            ->createCmsContentItemKeyMapper()
            ->mapContentItemKeyList($keyList);
    }
}
