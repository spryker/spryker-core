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
use Spryker\Client\SearchElasticsearch\Config\SortConfig;
use Spryker\Client\SearchExtension\Config\FacetConfigInterface;
use Spryker\Client\SearchExtension\Config\PaginationConfigInterface;
use Spryker\Client\SearchExtension\Config\SortConfigInterface;
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
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createFacetConfig(): FacetConfigInterface
    {
        return new FacetConfig();
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\PaginationConfigInterface
     */
    protected function createPaginationConfig(): PaginationConfigInterface
    {
        return new PaginationConfig();
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\SortConfigInterface
     */
    protected function createSortConfig(): SortConfigInterface
    {
        return new SortConfig();
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createStringSearchConfig(): FacetConfigInterface
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
            (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
        );

        return $facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createMultiStringSearchConfig(): FacetConfigInterface
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
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

        return $facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createIntegerSearchConfig(): FacetConfigInterface
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
            (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
        );

        return $facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createMultiIntegerSearchConfig(): FacetConfigInterface
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
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

        return $facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createCategorySearchConfig(): FacetConfigInterface
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
            (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SharedSearchElasticsearchConfig::FACET_TYPE_CATEGORY)
        );

        return $facetConfig;
    }
}
