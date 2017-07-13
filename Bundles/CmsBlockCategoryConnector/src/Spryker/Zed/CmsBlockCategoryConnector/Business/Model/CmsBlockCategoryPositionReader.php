<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Model;

use Generated\Shared\Transfer\CmsBlockCategoryPositionTransfer;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryPosition;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;

class CmsBlockCategoryPositionReader implements CmsBlockCategoryPositionReaderInterface
{

    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface
     */
    protected $cmsBlockCategoryConnectorQueryConnector;

    /**
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer
     */
    public function __construct(CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer)
    {
        $this->cmsBlockCategoryConnectorQueryConnector = $cmsBlockCategoryConnectorQueryContainer;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\CmsBlockCategoryPositionTransfer|null
     */
    public function findCmsBlockCategoryPositionByName($name)
    {
        $spyCmsBlockCategoryPosition = $this->cmsBlockCategoryConnectorQueryConnector
            ->queryCmsBlockCategoryPositionByName($name)
            ->findOne();

        if (!$spyCmsBlockCategoryPosition) {
            return null;
        }

        $cmsBlockCategoryPositionTransfer = $this->createCmsBlockCategoryPositionTransfer();
        $cmsBlockCategoryPositionTransfer = $this->mapEntityToTransfer($spyCmsBlockCategoryPosition, $cmsBlockCategoryPositionTransfer);

        return $cmsBlockCategoryPositionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CmsBlockCategoryPositionTransfer
     */
    protected function createCmsBlockCategoryPositionTransfer()
    {
        return new CmsBlockCategoryPositionTransfer();
    }

    /**
     * @param \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryPosition $spyCmsBlockCategoryPosition
     * @param \Generated\Shared\Transfer\CmsBlockCategoryPositionTransfer $cmsBlockCategoryPositionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockCategoryPositionTransfer
     */
    protected function mapEntityToTransfer(
        SpyCmsBlockCategoryPosition $spyCmsBlockCategoryPosition,
        CmsBlockCategoryPositionTransfer $cmsBlockCategoryPositionTransfer
    ) {
        $cmsBlockCategoryPositionTransfer->fromArray($spyCmsBlockCategoryPosition->toArray(), true);

        return $cmsBlockCategoryPositionTransfer;
    }

}
