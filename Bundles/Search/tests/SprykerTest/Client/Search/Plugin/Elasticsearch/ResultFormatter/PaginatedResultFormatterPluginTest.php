<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\PaginationConfigTransfer;
use Generated\Shared\Transfer\PaginationSearchResultTransfer;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\PaginatedResultFormatterPlugin;
use Spryker\Client\Search\SearchFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group ResultFormatter
 * @group PaginatedResultFormatterPluginTest
 * Add your own group annotations below this line
 */
class PaginatedResultFormatterPluginTest extends AbstractResultFormatterPluginTest
{
    /**
     * @dataProvider resultFormatterDataProvider
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface $searchConfig
     * @param array $totalHits
     * @param array $requestParameters
     * @param \Generated\Shared\Transfer\PaginationSearchResultTransfer $expectedResult
     *
     * @return void
     */
    public function testFormatResultShouldReturnCorrectFormat(SearchConfigInterface $searchConfig, $totalHits, array $requestParameters, PaginationSearchResultTransfer $expectedResult)
    {
        /** @var \Spryker\Client\Search\SearchFactory|\PHPUnit\Framework\MockObject\MockObject $searchFactoryMock */
        $searchFactoryMock = $this->getMockBuilder(SearchFactory::class)
            ->setMethods(['getSearchConfig'])
            ->getMock();
        $searchFactoryMock
            ->method('getSearchConfig')
            ->willReturn($searchConfig);

        $paginatedResultFormatterPlugin = new PaginatedResultFormatterPlugin();
        $paginatedResultFormatterPlugin->setFactory($searchFactoryMock);

        /** @var \Elastica\ResultSet|\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTotalHits'])
            ->getMock();
        $resultSetMock
            ->method('getTotalHits')
            ->willReturn($totalHits);

        $formattedResult = $paginatedResultFormatterPlugin->formatResult($resultSetMock, $requestParameters);

        $this->assertEquals($expectedResult, $formattedResult);
    }

    /**
     * @return array
     */
    public function resultFormatterDataProvider()
    {
        return [
            'first page should shown if there\'s no request parameters' => $this->getDataForFirstPageWithoutRequestParameters(),
            'explicit first page data' => $this->getDataForExplicitFirstPage(),
            'zero result data' => $this->getZeroResultData(),
            'page smaller then 1 should use the first page' => $this->getInvalidPageData(-1, 1),
            'page higher then the max page should use the last page' => $this->getInvalidPageData(11, 10),
            'valid items per page parameter should change the result number' => $this->getDataForValidItemsPerPageParameter(),
            'invalid items per page parameter should use the default value' => $this->getDataForInvalidItemsPerPageParameter(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForFirstPageWithoutRequestParameters()
    {
        $totalHits = 100;

        $searchConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [];

        $expectedResult = (new PaginationSearchResultTransfer())
            ->setNumFound(100)
            ->setCurrentPage(1)
            ->setMaxPage(10)
            ->setCurrentItemsPerPage(10)
            ->setConfig($searchConfig->getPaginationConfigBuilder()->get());

        return [$searchConfig, $totalHits, $requestParameters, $expectedResult];
    }

    /**
     * @return array
     */
    protected function getZeroResultData()
    {
        $totalHits = 0;

        $searchConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [];

        $expectedResult = (new PaginationSearchResultTransfer())
            ->setNumFound(0)
            ->setCurrentPage(0)
            ->setMaxPage(0)
            ->setCurrentItemsPerPage(10)
            ->setConfig($searchConfig->getPaginationConfigBuilder()->get());

        return [$searchConfig, $totalHits, $requestParameters, $expectedResult];
    }

    /**
     * @return array
     */
    protected function getDataForExplicitFirstPage()
    {
        $totalHits = 100;

        $searchConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [
            'page' => 1,
        ];

        $expectedResult = (new PaginationSearchResultTransfer())
            ->setNumFound(100)
            ->setCurrentPage(1)
            ->setMaxPage(10)
            ->setCurrentItemsPerPage(10)
            ->setConfig($searchConfig->getPaginationConfigBuilder()->get());

        return [$searchConfig, $totalHits, $requestParameters, $expectedResult];
    }

    /**
     * @param int $page
     * @param int $expectedPage
     *
     * @return array
     */
    protected function getInvalidPageData($page, $expectedPage)
    {
        $totalHits = 100;

        $searchConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [
            'page' => $page,
        ];

        $expectedResult = (new PaginationSearchResultTransfer())
            ->setNumFound(100)
            ->setCurrentPage($expectedPage)
            ->setMaxPage(10)
            ->setCurrentItemsPerPage(10)
            ->setConfig($searchConfig->getPaginationConfigBuilder()->get());

        return [$searchConfig, $totalHits, $requestParameters, $expectedResult];
    }

    /**
     * @return array
     */
    protected function getDataForValidItemsPerPageParameter()
    {
        $totalHits = 100;

        $searchConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [
            'ipp' => 50,
        ];

        $expectedResult = (new PaginationSearchResultTransfer())
            ->setNumFound(100)
            ->setCurrentPage(1)
            ->setMaxPage(2)
            ->setCurrentItemsPerPage(50)
            ->setConfig($searchConfig->getPaginationConfigBuilder()->get());

        return [$searchConfig, $totalHits, $requestParameters, $expectedResult];
    }

    /**
     * @return array
     */
    protected function getDataForInvalidItemsPerPageParameter()
    {
        $totalHits = 100;

        $searchConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [
            'ipp' => 15,
        ];

        $expectedResult = (new PaginationSearchResultTransfer())
            ->setNumFound(100)
            ->setCurrentPage(1)
            ->setMaxPage(10)
            ->setCurrentItemsPerPage(10)
            ->setConfig($searchConfig->getPaginationConfigBuilder()->get());

        return [$searchConfig, $totalHits, $requestParameters, $expectedResult];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createSimpleSearchConfigMock()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig
            ->getPaginationConfigBuilder()
            ->setPagination((new PaginationConfigTransfer())
                ->setParameterName('page')
                ->setItemsPerPageParameterName('ipp')
                ->setDefaultItemsPerPage(10)
                ->setValidItemsPerPageOptions([10, 50, 100]));

        return $searchConfig;
    }
}
