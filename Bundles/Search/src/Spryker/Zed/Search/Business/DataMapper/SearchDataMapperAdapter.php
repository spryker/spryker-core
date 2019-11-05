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

class SearchDataMapperAdapter implements PageDataMapperInterface
{
    /**
     * @var \Spryker\Zed\Search\Business\DataMapper\SearchDataMapperInterface
     */
    protected $searchDataMapper;

    public function __construct(SearchDataMapperInterface $searchDataMapper)
    {
        $this->searchDataMapper = $searchDataMapper;
    }

    /**
     * @deprecated Use transferDataByMapperName() instead.
     *
     * @param \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface $pageMap
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapData(PageMapInterface $pageMap, array $data, LocaleTransfer $localeTransfer)
    {
        // TODO: Implement mapData() method.
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
        $dataMappingContextTransfer->setLocale($localeTransfer)->setMapperName($mapperName);

        return $this->searchDataMapper->mapRawDataToSearchData($data, $dataMappingContextTransfer);
    }
}
