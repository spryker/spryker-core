<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\AclEntity;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group AclEntity
 * @group AclEntityServiceTest
 * Add your own group annotations below this line
 */
class AclEntityServiceTest extends Unit
{
    /**
     * @var string
     */
    protected const BASE_TABLE_NAME = 'base_table';

    /**
     * @var string
     */
    protected const CONNECTOR_TABLE_NAME = 'connector_table';

    /**
     * @var string
     */
    protected const BASE_CLASS_NAME = '/path/to/BaseClass';

    /**
     * @var string
     */
    protected const BASE_CLASS_SHORT_NAME = 'BaseClass';

    /**
     * @var string
     */
    protected const EXPECTED_SEGMENT_CONNECTOR_TABLE_NAME = 'spy_acl_entity_segment_base_table';

    /**
     * @var string
     */
    protected const EXPECTED_SEGMENT_CONNECTOR_PRIMARY_KEY = 'id_connector_table';

    /**
     * @var string
     */
    protected const EXPECTED_SEGMENT_CONNECTOR_CLASS = '/path/to/SpyAclEntitySegmentBaseClass';

    /**
     * @var string
     */
    protected const EXPECTED_SEGMENT_CONNECTOR_RELATION_NAME = 'BaseClass.SpyAclEntitySegmentBaseClass';

    /**
     * @var string
     */
    protected const EXPECTED_SEGMENT_CONNECTOR_GETTER = 'getSpyAclEntitySegmentBaseClasss';

    /**
     * @var string
     */
    protected const EXPECTED_SEGMENT_CONNECTOR_REFERENCE_GETTER = 'getFkBaseClass';

    /**
     * @var string
     */
    protected const EXPECTED_SEGMENT_CONNECTOR_REFERENCE_SETTER = 'setFkBaseClass';

    /**
     * @var string
     */
    protected const EXPECTED_SEGMENT_CONNECTOR_REFERENCE_COLUMN = 'fk_base_table';

    /**
     * @var \SprykerTest\Service\AclEntity\AclEntityServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateConnectorTableNameSuccessful(): void
    {
        // Act
        $aclEntityService = $this->tester->getAclEntityService();
        $connectorTableName = $aclEntityService->generateSegmentConnectorTableName(static::BASE_TABLE_NAME);

        // Assert
        $this->assertSame(static::EXPECTED_SEGMENT_CONNECTOR_TABLE_NAME, $connectorTableName);
    }

    /**
     * @return void
     */
    public function testGenerateConnectorTableIdColumnNameSuccessful(): void
    {
        // Act
        $aclEntityService = $this->tester->getAclEntityService();
        $connectorTableIdColumnName = $aclEntityService->generateSegmentConnectorTableIdColumnName(static::CONNECTOR_TABLE_NAME);

        // Assert
        $this->assertSame(static::EXPECTED_SEGMENT_CONNECTOR_PRIMARY_KEY, $connectorTableIdColumnName);
    }

    /**
     * @return void
     */
    public function testGenerateConnectorClassNameSuccessful(): void
    {
        // Act
        $aclEntityService = $this->tester->getAclEntityService();
        $connectorClassName = $aclEntityService->generateSegmentConnectorClassName(static::BASE_CLASS_NAME);

        // Assert
        $this->assertSame(static::EXPECTED_SEGMENT_CONNECTOR_CLASS, $connectorClassName);
    }

    /**
     * @return void
     */
    public function testGenerateConnectorRelationNameSuccessful(): void
    {
        // Act
        $aclEntityService = $this->tester->getAclEntityService();
        $connectorRelationName = $aclEntityService->generateSegmentConnectorRelationName(static::BASE_CLASS_SHORT_NAME);

        // Assert
        $this->assertSame(static::EXPECTED_SEGMENT_CONNECTOR_RELATION_NAME, $connectorRelationName);
    }

    /**
     * @return void
     */
    public function testGenerateConnectorGetterSuccessful(): void
    {
        // Act
        $aclEntityService = $this->tester->getAclEntityService();
        $connectorConnectorGetter = $aclEntityService->generateSegmentConnectorGetter(static::BASE_CLASS_NAME);

        // Assert
        $this->assertSame(static::EXPECTED_SEGMENT_CONNECTOR_GETTER, $connectorConnectorGetter);
    }

    /**
     * @return void
     */
    public function testGenerateConnectorReferenceGetterSuccessful(): void
    {
        // Act
        $aclEntityService = $this->tester->getAclEntityService();
        $connectorReferenceGetter = $aclEntityService->generateSegmentConnectorReferenceGetter(static::BASE_CLASS_NAME);

        // Assert
        $this->assertSame(static::EXPECTED_SEGMENT_CONNECTOR_REFERENCE_GETTER, $connectorReferenceGetter);
    }

    /**
     * @return void
     */
    public function testGenerateConnectorReferenceSetterSuccessful(): void
    {
        // Act
        $aclEntityService = $this->tester->getAclEntityService();
        $connectorReferenceGetter = $aclEntityService->generateSegmentConnectorReferenceSetter(static::BASE_CLASS_NAME);

        // Assert
        $this->assertSame(static::EXPECTED_SEGMENT_CONNECTOR_REFERENCE_SETTER, $connectorReferenceGetter);
    }

    /**
     * @return void
     */
    public function testGenerateConnectorReferenceColumnNameSuccessful(): void
    {
        // Act
        $aclEntityService = $this->tester->getAclEntityService();
        $connectorReferenceColumnName = $aclEntityService->generateSegmentConnectorReferenceColumnName(static::BASE_TABLE_NAME);

        // Assert
        $this->assertSame(static::EXPECTED_SEGMENT_CONNECTOR_REFERENCE_COLUMN, $connectorReferenceColumnName);
    }
}
