<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\DataMapper;

use Generated\Shared\Transfer\DataMappingContextTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageDataMapperInterface;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

/**
 * @deprecated Use `\Spryker\Zed\Search\Business\DataMapper\SearchDataMapperInterface` instead.
 */
class SearchDataMapperToPageDataMapperAdapter implements PageDataMapperInterface
{
    /**
     * @var \Spryker\Zed\Search\Business\DataMapper\SearchDataMapperInterface
     */
    protected $searchDataMapper;

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageDataMapperInterface
     */
    protected $pageDataMapper;

    /**
     * @param \Spryker\Zed\Search\Business\DataMapper\SearchDataMapperInterface $searchDataMapper
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageDataMapperInterface $pageDataMapper
     */
    public function __construct(SearchDataMapperInterface $searchDataMapper, PageDataMapperInterface $pageDataMapper)
    {
        $this->searchDataMapper = $searchDataMapper;
        $this->pageDataMapper = $pageDataMapper;
    }

    /**
     * @deprecated Use mapRawDataToSearchData() instead.
     *
     * @param \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface $pageMap
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapData(PageMapInterface $pageMap, array $data, LocaleTransfer $localeTransfer)
    {
        return $this->pageDataMapper->mapData($pageMap, $data, $localeTransfer);
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $mapperName
     *
     * @return array
     */
    public function transferDataByMapperName(array $data, LocaleTransfer $localeTransfer, $mapperName)
    {
        $dataMappingContextTransfer = new DataMappingContextTransfer();
        $dataMappingContextTransfer->setLocale($localeTransfer)->setResourceName($mapperName);

        return $this->searchDataMapper->mapRawDataToSearchData($data, $dataMappingContextTransfer);
    }
}
