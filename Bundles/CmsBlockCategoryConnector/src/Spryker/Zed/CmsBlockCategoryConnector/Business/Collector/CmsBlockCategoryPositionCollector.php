<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Collector;


use Spryker\Shared\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\Collector\Storage\Propel\CmsBlockCategoryPositionCollectorQuery;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;

class CmsBlockCategoryPositionCollector extends AbstractStoragePropelCollector
{

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return CmsBlockCategoryConnectorConfig::RESOURCE_TYPE_CMS_BLOCK_CATEGORY_POSITION;
    }

    /**
     * @param mixed $data
     * @param string $localeName
     * @param array $collectedItemData
     *
     * @return string
     */
    protected function collectKey($data, $localeName, array $collectedItemData)
    {
        return parent::collectKey(
            $collectedItemData[CmsBlockCategoryPositionCollectorQuery::COL_POSITION_NAME] .
            $collectedItemData[CmsBlockCategoryPositionCollectorQuery::COL_ID_CATEGORY],
            $localeName,
            $collectedItemData
        );
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        return $this->extractCmsBlockNames($collectItemData[CmsBlockCategoryPositionCollectorQuery::COL_CMS_BLOCK_NAMES]);
    }

    /**
     * @param string $cmsBlockNames
     *
     * @return array
     */
    protected function extractCmsBlockNames($cmsBlockNames)
    {
        $separator = ',';
        return explode($separator, trim($cmsBlockNames));
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }

}