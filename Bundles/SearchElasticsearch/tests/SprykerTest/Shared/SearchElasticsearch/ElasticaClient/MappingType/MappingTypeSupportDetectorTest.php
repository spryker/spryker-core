<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SearchElasticsearch\ElasticaClient\MappingType;

use Codeception\Test\Unit;
use Spryker\Shared\SearchElasticsearch\MappingType\MappingTypeSupportDetector;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group SearchElasticsearch
 * @group ElasticaClient
 * @group MappingType
 * @group MappingTypeSupportDetectorTest
 * Add your own group annotations below this line
 */
class MappingTypeSupportDetectorTest extends Unit
{
    /**
     * @return void
     */
    public function testCanDetectMappingTypeSupport(): void
    {
        // Arrange
        $supportsMappingTypes = class_exists('\Elastica\Type');
        $mappingTypeSupportDetector = new MappingTypeSupportDetector();

        // Act
        $result = $mappingTypeSupportDetector->isMappingTypesSupported();

        // Assert
        $this->assertEquals($supportsMappingTypes, $result);
    }
}
