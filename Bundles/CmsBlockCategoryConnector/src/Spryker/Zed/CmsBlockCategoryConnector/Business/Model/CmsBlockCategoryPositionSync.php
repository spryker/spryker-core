<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Model;

use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryPosition;
use Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;

class CmsBlockCategoryPositionSync implements CmsBlockCategoryPositionSyncInterface
{
    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface
     */
    protected $cmsBlockCategoryConnectorQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig
     */
    protected $cmsBlockCategoryConnectorConfig;

    /**
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer
     * @param \Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig $cmsBlockCategoryConnectorConfig
     */
    public function __construct(
        CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer,
        CmsBlockCategoryConnectorConfig $cmsBlockCategoryConnectorConfig
    ) {
        $this->cmsBlockCategoryConnectorQueryContainer = $cmsBlockCategoryConnectorQueryContainer;
        $this->cmsBlockCategoryConnectorConfig = $cmsBlockCategoryConnectorConfig;
    }

    /**
     * @return void
     */
    public function syncFromConfig()
    {
        $positionList = $this->cmsBlockCategoryConnectorConfig
            ->getCmsBlockCategoryPositionList();

        foreach ($positionList as $positionName) {
            $spyCmsBlockCategoryPosition = $this->cmsBlockCategoryConnectorQueryContainer
                ->queryCmsBlockCategoryPositionByName($positionName)
                ->findOne();

            if (!$spyCmsBlockCategoryPosition) {
                $spyCmsBlockCategoryPosition = $this->createSpyCmsBlockCategoryPosition();
                $spyCmsBlockCategoryPosition->setName($positionName);
                $spyCmsBlockCategoryPosition->save();
            }
        }
    }

    /**
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryPosition
     */
    protected function createSpyCmsBlockCategoryPosition()
    {
        return new SpyCmsBlockCategoryPosition();
    }
}
