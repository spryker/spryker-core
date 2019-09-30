<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\PaginationConfigTransfer;
use Generated\Shared\Transfer\PaginationSearchResultTransfer;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\PaginatedResultFormatterPlugin;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory;
use Spryker\Client\SearchExtension\Config\PaginationConfigInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
 * @group ResultFormatter
 * @group PaginatedResultFormatterPluginTest
 * Add your own group annotations below this line
 */
class PaginatedResultFormatterPluginTest extends AbstractResultFormatterPluginTest
{
    /**
     * @dataProvider resultFormatterDataProvider
     *
     * @param \Spryker\Client\SearchExtension\Config\PaginationConfigInterface $paginationConfig
     * @param int $totalHits
     * @param array $requestParameters
     * @param \Generated\Shared\Transfer\PaginationSearchResultTransfer $expectedResult
     *
     * @return void
     */
    public function testFormatResultShouldReturnCorrectFormat(
        PaginationConfigInterface $paginationConfig,
        int $totalHits,
        array $requestParameters,
        PaginationSearchResultTransfer $expectedResult
    ): void {
        // Arrange
        /** @var \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory|\PHPUnit\Framework\MockObject\MockObject $searchFactoryMock */
        $searchFactoryMock = $this->getMockBuilder(SearchElasticsearchFactory::class)
            ->setMethods(['getPaginationConfig'])
            ->getMock();
        $searchFactoryMock
            ->method('getPaginationConfig')
            ->willReturn($paginationConfig);

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

        // Act
        $formattedResult = $paginatedResultFormatterPlugin->formatResult($resultSetMock, $requestParameters);

        // Assert
        $this->assertEquals($expectedResult, $formattedResult);
    }

    /**
     * @return array
     */
    public function resultFormatterDataProvider(): array
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
    protected function getDataForFirstPageWithoutRequestParameters(): array
    {
        $totalHits = 100;

        $paginationConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [];

        $expectedResult = (new PaginationSearchResultTransfer())
            ->setNumFound(100)
            ->setCurrentPage(1)
            ->setMaxPage(10)
            ->setCurrentItemsPerPage(10)
            ->setConfig($paginationConfig->get());

        return [$paginationConfig, $totalHits, $requestParameters, $expectedResult];
    }

    /**
     * @return array
     */
    protected function getZeroResultData(): array
    {
        $totalHits = 0;

        $paginationConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [];

        $expectedResult = (new PaginationSearchResultTransfer())
            ->setNumFound(0)
            ->setCurrentPage(0)
            ->setMaxPage(0)
            ->setCurrentItemsPerPage(10)
            ->setConfig($paginationConfig->get());

        return [$paginationConfig, $totalHits, $requestParameters, $expectedResult];
    }

    /**
     * @return array
     */
    protected function getDataForExplicitFirstPage(): array
    {
        $totalHits = 100;

        $paginationConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [
            'page' => 1,
        ];

        $expectedResult = (new PaginationSearchResultTransfer())
            ->setNumFound(100)
            ->setCurrentPage(1)
            ->setMaxPage(10)
            ->setCurrentItemsPerPage(10)
            ->setConfig($paginationConfig->get());

        return [$paginationConfig, $totalHits, $requestParameters, $expectedResult];
    }

    /**
     * @param int $page
     * @param int $expectedPage
     *
     * @return array
     */
    protected function getInvalidPageData(int $page, int $expectedPage): array
    {
        $totalHits = 100;

        $paginationConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [
            'page' => $page,
        ];

        $expectedResult = (new PaginationSearchResultTransfer())
            ->setNumFound(100)
            ->setCurrentPage($expectedPage)
            ->setMaxPage(10)
            ->setCurrentItemsPerPage(10)
            ->setConfig($paginationConfig->get());

        return [$paginationConfig, $totalHits, $requestParameters, $expectedResult];
    }

    /**
     * @return array
     */
    protected function getDataForValidItemsPerPageParameter(): array
    {
        $totalHits = 100;

        $paginationConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [
            'ipp' => 50,
        ];

        $expectedResult = (new PaginationSearchResultTransfer())
            ->setNumFound(100)
            ->setCurrentPage(1)
            ->setMaxPage(2)
            ->setCurrentItemsPerPage(50)
            ->setConfig($paginationConfig->get());

        return [$paginationConfig, $totalHits, $requestParameters, $expectedResult];
    }

    /**
     * @return array
     */
    protected function getDataForInvalidItemsPerPageParameter(): array
    {
        $totalHits = 100;

        $paginationConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [
            'ipp' => 15,
        ];

        $expectedResult = (new PaginationSearchResultTransfer())
            ->setNumFound(100)
            ->setCurrentPage(1)
            ->setMaxPage(10)
            ->setCurrentItemsPerPage(10)
            ->setConfig($paginationConfig->get());

        return [$paginationConfig, $totalHits, $requestParameters, $expectedResult];
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\PaginationConfigInterface
     */
    protected function createSimpleSearchConfigMock(): PaginationConfigInterface
    {
        $paginationConfig = $this->createPaginationConfig();
        $paginationConfig->setPagination((new PaginationConfigTransfer())
                ->setParameterName('page')
                ->setItemsPerPageParameterName('ipp')
                ->setDefaultItemsPerPage(10)
                ->setValidItemsPerPageOptions([10, 50, 100]));

        return $paginationConfig;
    }
}
