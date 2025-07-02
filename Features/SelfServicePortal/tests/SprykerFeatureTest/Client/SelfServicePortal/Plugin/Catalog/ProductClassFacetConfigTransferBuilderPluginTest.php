<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Client\SelfServicePortal\Plugin\Catalog;

use Codeception\Test\Unit;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Shared\Search\SearchConfig;
use SprykerFeature\Client\SelfServicePortal\Plugin\Catalog\ProductClassFacetConfigTransferBuilderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Client
 * @group SelfServicePortal
 * @group Plugin
 * @group Catalog
 * @group ProductClassFacetConfigTransferBuilderPluginTest
 * Add your own group annotations below this line
 */
class ProductClassFacetConfigTransferBuilderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FACET_NAME = 'product-class-names';

    /**
     * @var string
     */
    protected const PARAMETER_NAME = 'product-class-names';

    /**
     * @return void
     */
    public function testBuildShouldReturnCorrectlyConfiguredFacetConfigTransfer(): void
    {
        // Arrange
        $plugin = new ProductClassFacetConfigTransferBuilderPlugin();

        // Act
        $facetConfigTransfer = $plugin->build();

        // Assert
        $this->assertSame(static::FACET_NAME, $facetConfigTransfer->getName());
        $this->assertSame(static::PARAMETER_NAME, $facetConfigTransfer->getParameterName());
        $this->assertSame(PageIndexMap::STRING_FACET, $facetConfigTransfer->getFieldName());
        $this->assertSame(SearchConfig::FACET_TYPE_ENUMERATION, $facetConfigTransfer->getType());
        $this->assertTrue($facetConfigTransfer->getIsMultiValued());
    }
}
