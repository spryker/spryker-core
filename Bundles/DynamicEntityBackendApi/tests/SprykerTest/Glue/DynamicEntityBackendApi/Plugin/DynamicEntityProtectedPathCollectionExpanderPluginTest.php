<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DynamicEntityBackendApi\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;
use Spryker\Glue\DynamicEntityBackendApi\Plugin\GlueBackendApiApplicationAuthorizationConnector\DynamicEntityProtectedPathCollectionExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DynamicEntityBackendApi
 * @group Plugin
 * @group DynamicEntityProtectedPathCollectionExpanderPluginTest
 * Add your own group annotations below this line
 */
class DynamicEntityProtectedPathCollectionExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const IS_REGULAR_EXPRESSION = 'isRegularExpression';

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Plugin\GlueBackendApiApplicationAuthorizationConnector\DynamicEntityProtectedPathCollectionExpanderPlugin
     */
    protected DynamicEntityProtectedPathCollectionExpanderPlugin $dynamicEntityProtectedPathCollectionExpanderPlugin;

    /**
     * @var string
     */
    protected string $routePrefix;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->dynamicEntityProtectedPathCollectionExpanderPlugin = new DynamicEntityProtectedPathCollectionExpanderPlugin();
        $this->routePrefix = (new DynamicEntityBackendApiConfig())->getRoutePrefix();
    }

    /**
     * @return void
     */
    public function testExpandWithEmptyCollection(): void
    {
        // Arrange
        $expectedResult = [sprintf('/\/%s\/.+/', $this->routePrefix) => [static::IS_REGULAR_EXPRESSION => true]];
        $dynamicEntityGetCollectionEndpoint = sprintf('/%s/get-test-collection', $this->routePrefix);
        $dynamicEntityGetEndpoint = sprintf('/%s/get-test/1', $this->routePrefix);
        $wrongEndpoint = '/get-test/1';

        //Act
        $protectedPathCollection = $this->dynamicEntityProtectedPathCollectionExpanderPlugin->expand([]);

        //Assert
        $this->assertEquals($expectedResult, $protectedPathCollection);
        $protectedPathPattern = array_key_first($protectedPathCollection);
        $this->assertEquals(1, preg_match($protectedPathPattern, $dynamicEntityGetCollectionEndpoint));
        $this->assertEquals(1, preg_match($protectedPathPattern, $dynamicEntityGetEndpoint));
        $this->assertEquals(0, preg_match($protectedPathPattern, $wrongEndpoint));
    }

    /**
     * @return void
     */
    public function testExpandWithNonEmptyCollection(): void
    {
        // Arrange
        $inputCollection = [
            "/\/example\/\d+/" => [static::IS_REGULAR_EXPRESSION => true],
            "/\/test\/\w+/" => [static::IS_REGULAR_EXPRESSION => false],
        ];
        $expectedResult = [
            "/\/example\/\d+/" => [static::IS_REGULAR_EXPRESSION => true],
            "/\/test\/\w+/" => [static::IS_REGULAR_EXPRESSION => false],
            "/\/" . $this->routePrefix . "\/.+/" => [static::IS_REGULAR_EXPRESSION => true],
        ];

        //Act
        $protectedPathCollection = $this->dynamicEntityProtectedPathCollectionExpanderPlugin->expand($inputCollection);

        //Assert
        $this->assertEquals($expectedResult, $protectedPathCollection);
    }

    /**
     * @return void
     */
    public function testExpandWithEmptyCollections(): void
    {
        // Arrange
        $expectedResult = [
            "/\/" . $this->routePrefix . "\/.+/" => [static::IS_REGULAR_EXPRESSION => true],
        ];

        //Act
        $protectedPathCollection = $this->dynamicEntityProtectedPathCollectionExpanderPlugin->expand([]);

        //Assert
        $this->assertEquals($expectedResult, $protectedPathCollection);
    }
}
