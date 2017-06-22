<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Business\Model;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface;

class CmsBlockProductReader implements CmsBlockProductReaderInterface
{

    /**
     * @var \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface $queryContainer
     */
    public function __construct(
        CmsBlockProductConnectorQueryContainerInterface $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function hydrateProductRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $cmsBlockTransfer->requireIdCmsBlock();

        $idProductAbstracts = $this->queryContainer
            ->queryCmsBlockProductConnectorByIdCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->find()
            ->getColumnValues('FkProductAbstract');

        $cmsBlockTransfer->setIdProductAbstracts($idProductAbstracts);

        return $cmsBlockTransfer;
    }

}
