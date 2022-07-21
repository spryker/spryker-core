<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ConfigurableBundlesProductsResourceRelationship\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ConfigurableBundleTemplateSlotStorageBuilder;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\ConfigurableBundlesProductsResourceRelationshipDependencyProvider;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToCatalogClientInterface;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Plugin\GlueApplication\ProductConcreteByConfigurableBundleTemplateSlotResourceRelationshipPlugin;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResource;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ConfigurableBundlesProductsResourceRelationship
 * @group Plugin
 * @group GlueApplication
 * @group ProductConcreteByConfigurableBundleTemplateSlotResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class ProductConcreteByConfigurableBundleTemplateSlotResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\ConfigurableBundlesProductsResourceRelationshipConfig::RESOURCE_CONCRETE_PRODUCTS
     *
     * @var string
     */
    protected const RESOURCE_CONCRETE_PRODUCTS = 'concrete-products';

    /**
     * @uses \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\ConfigurableBundlesProductsResourceRelationshipConfig::RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOTS
     *
     * @var string
     */
    protected const RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOTS = 'configurable-bundle-template-slots';

    /**
     * @uses \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Reader\ProductConcreteReader::FORMATTED_RESULT_KEY
     *
     * @var string
     */
    protected const FORMATTED_RESULT_KEY = 'ProductConcreteCatalogSearchResultFormatterPlugin';

    /**
     * @var array<int, string>
     */
    protected const TEST_PRODUCT_CONCRETE_IDS_TO_PRODUCT_CONCRETE_SKUS_MAP = [
        1 => '001_0001',
        2 => '100',
        3 => 'test-sku',
    ];

    /**
     * @var \SprykerTest\Glue\ConfigurableBundlesProductsResourceRelationship\ConfigurableBundlesProductsResourceRelationshipPlugin
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddResourceRelationshipsWillAddProductConcreteResourcesWithNumericSkus(): void
    {
        // Arrange
        $productConcretePageSearchTransfers = $this->createProductConcretePageSearchTransfers(static::TEST_PRODUCT_CONCRETE_IDS_TO_PRODUCT_CONCRETE_SKUS_MAP);
        $restConcreteProductsResources = $this->createRestConcreteProductsResources(static::TEST_PRODUCT_CONCRETE_IDS_TO_PRODUCT_CONCRETE_SKUS_MAP);

        $this->tester->setDependency(
            ConfigurableBundlesProductsResourceRelationshipDependencyProvider::CLIENT_CATALOG,
            $this->getCatalogClientMock($productConcretePageSearchTransfers),
        );
        $this->tester->setDependency(
            ConfigurableBundlesProductsResourceRelationshipDependencyProvider::RESOURCE_PRODUCTS_REST_API,
            $this->getProductsRestApiResourceMock($restConcreteProductsResources),
        );

        $configurableBundleTemplateSlotStorageTransfer = (new ConfigurableBundleTemplateSlotStorageBuilder([
            ConfigurableBundleTemplateSlotStorageTransfer::ID_PRODUCT_LIST => 1,
        ]))->build();
        $restConfigurableBundleTemplateSlotsResource = new RestResource(static::RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOTS);
        $restConfigurableBundleTemplateSlotsResource->setPayload($configurableBundleTemplateSlotStorageTransfer);

        // Act
        (new ProductConcreteByConfigurableBundleTemplateSlotResourceRelationshipPlugin())->addResourceRelationships(
            [$restConfigurableBundleTemplateSlotsResource],
            $this->getRestRequestMock(),
        );

        // Assert
        $this->assertCount(3, $restConfigurableBundleTemplateSlotsResource->getRelationshipByType(static::RESOURCE_CONCRETE_PRODUCTS));
    }

    /**
     * @param array<int, string> $productConcreteIdsToProductConcreteSkusMap
     *
     * @return array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    protected function createProductConcretePageSearchTransfers(array $productConcreteIdsToProductConcreteSkusMap): array
    {
        $productConcretePageSearchTransfers = [];
        foreach ($productConcreteIdsToProductConcreteSkusMap as $idProductConcrete => $productConcreteSku) {
            $productConcretePageSearchTransfers[] = (new ProductConcretePageSearchTransfer())
                ->setSku($productConcreteSku)
                ->setFkProduct($idProductConcrete);
        }

        return $productConcretePageSearchTransfers;
    }

    /**
     * @param array<int, string> $productConcreteIdsToProductConcreteSkusMap
     *
     * @return array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    protected function createRestConcreteProductsResources(array $productConcreteIdsToProductConcreteSkusMap): array
    {
        $restConcreteProductsResources = [];
        foreach ($productConcreteIdsToProductConcreteSkusMap as $productConcreteSku) {
            $restConcreteProductsResources[] = new RestResource(static::RESOURCE_CONCRETE_PRODUCTS, $productConcreteSku);
        }

        return $restConcreteProductsResources;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer> $productConcretePageSearchTransfers
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToCatalogClientInterface
     */
    protected function getCatalogClientMock(
        array $productConcretePageSearchTransfers
    ): ConfigurableBundlesProductsResourceRelationshipToCatalogClientInterface {
        $catalogClientMock = $this->getMockBuilder(ConfigurableBundlesProductsResourceRelationshipToCatalogClientInterface::class)->getMock();
        $catalogClientMock->method('searchProductConcretesByFullText')->willReturn([
            static::FORMATTED_RESULT_KEY => $productConcretePageSearchTransfers,
        ]);

        return $catalogClientMock;
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $productConcreteResources
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface
     */
    protected function getProductsRestApiResourceMock(
        array $productConcreteResources
    ): ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface {
        $productsRestApiResourceMock = $this->getMockBuilder(ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface::class)->getMock();
        $productsRestApiResourceMock->method('getProductConcreteCollectionByIds')
            ->willReturn($productConcreteResources);

        return $productsRestApiResourceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected function getRestRequestMock(): RestRequestInterface
    {
        return $this->getMockBuilder(RestRequestInterface::class)->getMock();
    }
}
