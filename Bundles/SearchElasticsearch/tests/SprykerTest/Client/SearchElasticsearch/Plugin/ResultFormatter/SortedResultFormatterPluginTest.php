<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\SortConfigTransfer;
use Generated\Shared\Transfer\SortSearchResultTransfer;
use Spryker\Client\SearchElasticsearch\Config\SortConfig;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\SortedResultFormatterPlugin;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory;
use Spryker\Client\SearchExtension\Config\SortConfigInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
 * @group ResultFormatter
 * @group SortedResultFormatterPluginTest
 * Add your own group annotations below this line
 */
class SortedResultFormatterPluginTest extends AbstractResultFormatterPluginTest
{
    /**
     * @dataProvider resultFormatterDataProvider
     *
     * @param \Spryker\Client\SearchExtension\Config\SortConfigInterface $sortConfig
     * @param array $requestParameters
     * @param \Generated\Shared\Transfer\SortSearchResultTransfer $expectedResult
     *
     * @return void
     */
    public function testFormatResultShouldReturnCorrectFormat(SortConfigInterface $sortConfig, array $requestParameters, SortSearchResultTransfer $expectedResult): void
    {
        /** @var \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory|\PHPUnit\Framework\MockObject\MockObject $searchFactoryMock */
        $searchFactoryMock = $this->getMockBuilder(SearchElasticsearchFactory::class)
            ->setMethods(['getSortConfig'])
            ->getMock();
        $searchFactoryMock
            ->method('getSortConfig')
            ->willReturn($sortConfig);

        $sortedResultFormatterPlugin = new SortedResultFormatterPlugin();
        $sortedResultFormatterPlugin->setFactory($searchFactoryMock);

        /** @var \Elastica\ResultSet|\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->getMock();

        $formattedResult = $sortedResultFormatterPlugin->formatResult($resultSetMock, $requestParameters);

        $this->assertEquals($expectedResult, $formattedResult);
    }

    /**
     * @return array
     */
    public function resultFormatterDataProvider(): array
    {
        return [
            'no active sort when it\'s not requested' => $this->getDataForInactiveSort(),
            'activate sort when it\'s requested' => $this->getDataForActiveSort(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForInactiveSort(): array
    {
        $sortConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [];

        $expectedResult = (new SortSearchResultTransfer())
            ->setSortParamNames(['foo-param'])
            ->setCurrentSortParam(null)
            ->setCurrentSortOrder(null);

        return [$sortConfig, $requestParameters, $expectedResult];
    }

    /**
     * @return array
     */
    protected function getDataForActiveSort(): array
    {
        $sortConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [
            SortConfig::DEFAULT_SORT_PARAM_KEY => 'foo-param',
        ];

        $expectedResult = (new SortSearchResultTransfer())
            ->setSortParamNames(['foo-param'])
            ->setCurrentSortParam('foo-param')
            ->setCurrentSortOrder(SortConfig::DIRECTION_ASC);

        return [$sortConfig, $requestParameters, $expectedResult];
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\SortConfigInterface
     */
    protected function createSimpleSearchConfigMock(): SortConfigInterface
    {
        $sortConfig = $this->createSortConfig();
        $sortConfig->addSort((new SortConfigTransfer())
                ->setName('foo')
                ->setParameterName('foo-param')
                ->setFieldName('foo'));

        return $sortConfig;
    }
}
