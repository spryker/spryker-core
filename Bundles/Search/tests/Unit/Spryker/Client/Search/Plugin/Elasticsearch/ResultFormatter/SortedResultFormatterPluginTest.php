<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\SortConfigTransfer;
use Generated\Shared\Transfer\SortSearchResultTransfer;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\Search\Plugin\Config\SortConfigBuilder;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\SortedResultFormatterPlugin;
use Spryker\Client\Search\SearchFactory;
use Unit\Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\AbstractResultFormatterPluginTest;

/**
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group ResultFormatter
 */
class SortedResultFormatterPluginTest extends AbstractResultFormatterPluginTest
{

    /**
     * @dataProvider resultFormatterDataProvider
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface $searchConfig
     * @param array $requestParameters
     * @param \Generated\Shared\Transfer\SortSearchResultTransfer $expectedResult
     *
     * @return void
     */
    public function testFormatResultShouldReturnCorrectFormat(SearchConfigInterface $searchConfig, array $requestParameters, SortSearchResultTransfer $expectedResult)
    {
        /** @var \Spryker\Client\Search\SearchFactory|\PHPUnit_Framework_MockObject_MockObject $searchFactoryMock */
        $searchFactoryMock = $this->getMockBuilder(SearchFactory::class)
            ->setMethods(['getSearchConfig'])
            ->getMock();
        $searchFactoryMock
            ->method('getSearchConfig')
            ->willReturn($searchConfig);

        $sortedResultFormatterPlugin = new SortedResultFormatterPlugin();
        $sortedResultFormatterPlugin->setFactory($searchFactoryMock);

        /** @var \Elastica\ResultSet|\PHPUnit_Framework_MockObject_MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->getMock();

        $formattedResult = $sortedResultFormatterPlugin->formatResult($resultSetMock, $requestParameters);

        $this->assertEquals($expectedResult, $formattedResult);
    }

    /**
     * @return array
     */
    public function resultFormatterDataProvider()
    {
        return [
            'no active sort when it\'s not requested' => $this->getDataForInactiveSort(),
            'activate sort when it\'s requested' => $this->getDataForActiveSort(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForInactiveSort()
    {
        $searchConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [];

        $expectedResult = (new SortSearchResultTransfer())
            ->setSortParamNames(['foo'])
            ->setCurrentSortParam(null)
            ->setCurrentSortOrder(null);

        return [$searchConfig, $requestParameters, $expectedResult];
    }

    /**
     * @return array
     */
    protected function getDataForActiveSort()
    {
        $searchConfig = $this->createSimpleSearchConfigMock();

        $requestParameters = [
            SortConfigBuilder::DEFAULT_SORT_PARAM_KEY => 'foo',
        ];

        $expectedResult = (new SortSearchResultTransfer())
            ->setSortParamNames(['foo'])
            ->setCurrentSortParam('foo')
            ->setCurrentSortOrder(SortConfigBuilder::DIRECTION_ASC);

        return [$searchConfig, $requestParameters, $expectedResult];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createSimpleSearchConfigMock()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig
            ->getSortConfigBuilder()
            ->addSort((new SortConfigTransfer())
                ->setName('foo')
                ->setParameterName('foo')
                ->setFieldName('foo'));

        return $searchConfig;
    }

}
