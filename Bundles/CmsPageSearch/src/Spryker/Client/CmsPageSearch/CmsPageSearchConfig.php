<?php

namespace Spryker\Client\CmsPageSearch;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface;
use Spryker\Client\Kernel\AbstractBundleConfig;

class CmsPageSearchConfig extends AbstractBundleConfig
{
    /**
     * @param \Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface $sortConfigBuilder
     *
     * @return \Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface
     */
    public function buildCmsPageSortConfig(SortConfigBuilderInterface $sortConfigBuilder): SortConfigBuilderInterface
    {
        $nameAscendingConfigTransfer = (new SortConfigTransfer())
            ->setName('name')
            ->setParameterName('name_asc')
            ->setFieldName(PageIndexMap::STRING_SORT);
        $nameDescendingConfigTransfer = (new SortConfigTransfer())
            ->setName('name')
            ->setParameterName('name_desc')
            ->setFieldName(PageIndexMap::STRING_SORT)
            ->setIsDescending(true);

        $sortConfigBuilder
            ->addSort($nameAscendingConfigTransfer)
            ->addSort($nameDescendingConfigTransfer);

        return $sortConfigBuilder;
    }
}
