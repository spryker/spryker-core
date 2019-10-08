<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\ResultFormatter;

use Codeception\Test\Unit;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\SearchElasticsearch\Config\FacetConfig;
use Spryker\Client\SearchElasticsearch\Config\PaginationConfig;
use Spryker\Client\SearchElasticsearch\Config\SearchConfig;
use Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface;
use Spryker\Client\SearchElasticsearch\Config\SortConfig;
use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig as SharedSearchElasticsearchConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
 * @group ResultFormatter
 * @group AbstractResultFormatterPluginTest
 * Add your own group annotations below this line
 */
abstract class AbstractResultFormatterPluginTest extends Unit
{
    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSearchConfigMock()
    {
        $searchConfigMock = $this->getMockBuilder(SearchConfig::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFacetConfig', 'getSortConfig', 'getPaginationConfig'])
            ->getMock();

        $searchConfigMock
            ->method('getFacetConfig')
            ->willReturn(new FacetConfig());

        $searchConfigMock
            ->method('getSortConfig')
            ->willReturn(new SortConfig());

        $searchConfigMock
            ->method('getPaginationConfig')
            ->willReturn(new PaginationConfig());

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createStringSearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getFacetConfig()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            );

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createMultiStringSearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getFacetConfig()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            );

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createIntegerSearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getFacetConfig()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            );

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createMultiIntegerSearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getFacetConfig()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchElasticsearchConfig::FACET_TYPE_RANGE)
            );

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createCategorySearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getFacetConfig()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SharedSearchElasticsearchConfig::FACET_TYPE_CATEGORY)
            );

        return $searchConfigMock;
    }
}
