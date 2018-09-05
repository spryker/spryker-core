<?php

namespace Spryker\Client\CmsPageSearch;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface;
use Spryker\Client\Kernel\AbstractBundleConfig;

class CmsPageSearchConfig extends AbstractBundleConfig
{
    protected const CMS_PAGES_SORT_OPTIONS = [
        ['name' => 'name', 'parameterName' => 'name_asc', 'fieldName' => PageIndexMap::STRING_SORT, 'isDescending' => false],
        ['name' => 'name', 'parameterName' => 'name_desc', 'fieldName' => PageIndexMap::STRING_SORT, 'isDescending' => true],
    ];

    /**
     * @param \Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface $sortConfigBuilder
     *
     * @return \Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface
     */
    public function buildCmsPageSortConfig(SortConfigBuilderInterface $sortConfigBuilder): SortConfigBuilderInterface
    {
        $sortingOptions = $this->getCmsPageSortOptions();

        foreach ($sortingOptions as $option) {
            $sortConfigTransfer = (new SortConfigTransfer())
                ->setName($option['name'])
                ->setParameterName($option['parameterName'])
                ->setFieldName($option['fieldName'])
                ->setIsDescending($option['isDescending']);
            $sortConfigBuilder->addSort($sortConfigTransfer);
        }

        return $sortConfigBuilder;
    }

    /**
     * @return array
     */
    public function getCmsPageSortOptions(): array
    {
        return static::CMS_PAGES_SORT_OPTIONS;
    }
}
