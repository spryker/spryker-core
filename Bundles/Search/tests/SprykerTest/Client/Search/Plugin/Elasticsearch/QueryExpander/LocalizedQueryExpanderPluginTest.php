<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\LocalizedQueryExpanderPlugin;
use Spryker\Client\Search\SearchDependencyProvider;
use SprykerTest\Client\Search\SearchClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group LocalizedQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class LocalizedQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{
    /**
     * @var \SprykerTest\Client\Search\SearchClientTester
     */
    protected SearchClientTester $tester;

    /**
     * @dataProvider localizedQueryExpanderDataProvider
     *
     * @param \Elastica\Query $expectedQuery
     *
     * @return void
     */
    public function testLocalizedQueryExpanderShouldExpandTheBaseQueryAccordingToRequestParameters(Query $expectedQuery): void
    {
        $queryExpander = $this->createLocalizedQueryExpanderPluginMock();

        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin());

        $query = $query->getSearchQuery();

        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @dataProvider localizedQueryExpanderDataProvider
     *
     * @param \Elastica\Query $expectedQuery
     *
     * @return void
     */
    public function testLocalizedQueryExpanderShouldExpandTheBaseQueryAccordingToRequestParametersWithCurrentLocale(Query $expectedQuery): void
    {
        // Arrange
        $queryExpander = new LocalizedQueryExpanderPlugin();
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Search\Dependency\Facade\SearchToLocaleClientInterface $searchToLocaleClientMock */
        $searchToLocaleClientMock = $this->tester->createLocaleClient();
        $searchToLocaleClientMock->expects($this->once())->method('getCurrentLocale')->willReturn($this->tester::LOCALE);
        $this->tester->setDependency(SearchDependencyProvider::CLIENT_LOCALE, $searchToLocaleClientMock);

        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin());

        // Act
        $query = $query->getSearchQuery();

        // Assert
        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    public function localizedQueryExpanderDataProvider(): array
    {
        return [
            'simple locale filtered query' => $this->getDataForSimpleLocaleFilteredQuery(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForSimpleLocaleFilteredQuery(): array
    {
        $expectedQuery = (new Query())
            ->setQuery((new BoolQuery())
            ->addMust($this->getMatchQuery()->setField(PageIndexMap::LOCALE, SearchClientTester::LOCALE)));

        return [$expectedQuery];
    }

    /**
     * @return \Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\LocalizedQueryExpanderPlugin|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createLocalizedQueryExpanderPluginMock(): LocalizedQueryExpanderPlugin
    {
        /** @var \Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\LocalizedQueryExpanderPlugin|\PHPUnit\Framework\MockObject\MockObject $queryExpander */
        $queryExpander = $this->getMockBuilder(LocalizedQueryExpanderPlugin::class)
            ->setMethods(['getCurrentLocale'])
            ->getMock();

        $queryExpander
            ->method('getCurrentLocale')
            ->willReturn($this->tester::LOCALE);

        $queryExpander->setFactory($this->getSearchFactory());

        return $queryExpander;
    }

    /**
     * For compatibility with PHP 8.
     *
     * @return \Elastica\Query\MatchQuery|\Elastica\Query\Match
     */
    public function getMatchQuery()
    {
        $matchQueryClassName = class_exists(MatchQuery::class)
            ? MatchQuery::class
            : '\Elastica\Query\Match';

        return new $matchQueryClassName();
    }
}
