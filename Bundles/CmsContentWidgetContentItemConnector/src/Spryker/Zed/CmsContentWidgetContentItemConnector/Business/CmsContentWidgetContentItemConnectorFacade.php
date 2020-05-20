<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentItemConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsContentWidgetContentItemConnector\Business\CmsContentWidgetContentItemConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\CmsContentWidgetContentItemConnector\Persistence\CmsContentWidgetContentItemConnectorRepositoryInterface getRepository()
 */
class CmsContentWidgetContentItemConnectorFacade extends AbstractFacade implements CmsContentWidgetContentItemConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $keyList
     *
     * @return array
     */
    public function mapContentItemKeyList(array $keyList): array
    {
        return $this->getFactory()
            ->createCmsContentItemKeyMapper()
            ->mapContentItemKeyList($keyList);
    }
}
