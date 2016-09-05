<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\LocalizedQueryExpanderPlugin;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group LocalizedQueryExpanderPluginTest
 */
class LocalizedQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{

    /**
     * @dataProvider localizedQueryExpanderDataProvider
     *
     * @param \Elastica\Query $expectedQuery
     *
     * @return void
     */
    public function testLocalizedQueryExpanderShouldExpandTheBaseQueryAccordingToRequestParameters(Query $expectedQuery)
    {
        /** @var \Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\LocalizedQueryExpanderPlugin|\PHPUnit_Framework_MockObject_MockObject $queryExpander */
        $queryExpander = $this->getMockBuilder(LocalizedQueryExpanderPlugin::class)
            ->setMethods(['getCurrentLocale'])
            ->getMock();

        $queryExpander
            ->method('getCurrentLocale')
            ->willReturn('ab_CD');

        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin());

        $query = $query->getSearchQuery();

        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    public function localizedQueryExpanderDataProvider()
    {
        return [
            'simple locale filtered query' => $this->getDataForSimpleLocaleFilteredQuery(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForSimpleLocaleFilteredQuery()
    {
        $expectedQuery = (new Query())
            ->setQuery((new BoolQuery())
                ->addMust((new Match())
                    ->setField(PageIndexMap::LOCALE, 'ab_CD')));

        return [$expectedQuery];
    }

}
