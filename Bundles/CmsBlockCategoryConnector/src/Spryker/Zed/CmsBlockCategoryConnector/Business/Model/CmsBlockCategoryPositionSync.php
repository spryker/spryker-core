<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Model;


use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryPosition;
use Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;

class CmsBlockCategoryPositionSync implements CmsBlockCategoryPositionSyncInterface
{
    /**
     * @var CmsBlockCategoryConnectorQueryContainerInterface
     */
    protected $cmsBlockCategoryConnectorQueryContainer;

    /**
     * @var CmsBlockCategoryConnectorConfig
     */
    protected $cmsBlockCategoryConnectorConfig;

    /**
     * @param CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer
     * @param CmsBlockCategoryConnectorConfig $cmsBlockCategoryConnectorConfig
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
     * @return SpyCmsBlockCategoryPosition
     */
    protected function createSpyCmsBlockCategoryPosition()
    {
        return new SpyCmsBlockCategoryPosition();
    }

}