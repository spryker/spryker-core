<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Persistence;

interface CmsBlockProductConnectorQueryContainerInterface
{
    const COL_PRODUCT_ABSTRACT_NAME = 'name';
    const COL_PRODUCT_ABSTRACT_SKU = 'sku';

    /**
     * @api
     *
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductConnector();

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductConnectorByIdCmsBlock($idCmsBlock);

    /**
     * @api
     *
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductConnectorWithNameByIdCmsBlock($idCmsBlock, $idLocale);

}
