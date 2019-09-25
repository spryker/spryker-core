<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Codeception\Test\Unit;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;
use Spryker\Client\Search\Plugin\Config\PaginationConfigBuilder;
use Spryker\Client\Search\Plugin\Config\SearchConfig;
use Spryker\Client\Search\Plugin\Config\SortConfigBuilder;
use Spryker\Shared\Search\SearchConfig as SharedSearchConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group ResultFormatter
 * @group AbstractResultFormatterPluginTest
 * Add your own group annotations below this line
 */
abstract class AbstractResultFormatterPluginTest extends Unit
{
    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createSearchConfigMock()
    {
        $searchConfigMock = $this->getMockBuilder(SearchConfig::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFacetConfigBuilder', 'getPaginationConfigBuilder', 'getSortConfigBuilder'])
            ->getMock();

        $searchConfigMock
            ->method('getFacetConfigBuilder')
            ->willReturn(new FacetConfigBuilder());

        $searchConfigMock
            ->method('getPaginationConfigBuilder')
            ->willReturn(new PaginationConfigBuilder());

        $searchConfigMock
            ->method('getSortConfigBuilder')
            ->willReturn(new SortConfigBuilder());

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createStringSearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            );

        return $searchConfig;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createMultiStringSearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            );

        return $searchConfig;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createIntegerSearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            );

        return $searchConfig;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createMultiIntegerSearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_RANGE)
            );

        return $searchConfig;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createCategorySearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SharedSearchConfig::FACET_TYPE_CATEGORY)
            );

        return $searchConfig;
    }
}
