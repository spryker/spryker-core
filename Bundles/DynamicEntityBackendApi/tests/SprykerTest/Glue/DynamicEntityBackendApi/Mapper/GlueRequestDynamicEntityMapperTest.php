<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DynamicEntityBackendApi\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DynamicEntityBackendApi
 * @group Mapper
 * @group GlueRequestDynamicEntityMapperTest
 * Add your own group annotations below this line
 */
class GlueRequestDynamicEntityMapperTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new DynamicEntityBackendApiFactory();
    }

    /**
     * @return void
     */
    public function testMapperMapsIsTransactionalModeByDefault(): void
    {
        // Arrange
        $glueRequestDynamicEntityMapper = $this->factory->createGlueRequestDynamicEntityMapper();
        $glueRequestTransfer = $this->getGlueRequestTransferWithContentAndTransationalHeader();

        // Act
        $dynamicEntityCollectionRequestTransfer = $glueRequestDynamicEntityMapper
            ->mapGlueRequestToDynamicEntityCollectionRequestTransfer($glueRequestTransfer);

        // Assert
        $this->assertTrue($dynamicEntityCollectionRequestTransfer->getIsTransactional());
    }

    /**
     * @return void
     */
    public function testMapperMapsNonTransactionalModeWhenHeaderIsFalseAsString(): void
    {
        // Arrange
        $glueRequestDynamicEntityMapper = $this->factory->createGlueRequestDynamicEntityMapper();
        $glueRequestTransfer = $this->getGlueRequestTransferWithContentAndTransationalHeader('false');

        // Act
        $dynamicEntityCollectionRequestTransfer = $glueRequestDynamicEntityMapper
            ->mapGlueRequestToDynamicEntityCollectionRequestTransfer($glueRequestTransfer);

        // Assert
        $this->assertFalse($dynamicEntityCollectionRequestTransfer->getIsTransactional());
    }

    /**
     * @return void
     */
    public function testMapperMapsNonTransactionalModeWhenHeaderIsZeroAsString(): void
    {
        // Arrange
        $glueRequestDynamicEntityMapper = $this->factory->createGlueRequestDynamicEntityMapper();
        $glueRequestTransfer = $this->getGlueRequestTransferWithContentAndTransationalHeader('0');

        // Act
        $dynamicEntityCollectionRequestTransfer = $glueRequestDynamicEntityMapper
            ->mapGlueRequestToDynamicEntityCollectionRequestTransfer($glueRequestTransfer);

        // Assert
        $this->assertFalse($dynamicEntityCollectionRequestTransfer->getIsTransactional());
    }

    /**
     * @param string|null $isTransactionalHeaderValue
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function getGlueRequestTransferWithContentAndTransationalHeader(?string $isTransactionalHeaderValue = null): GlueRequestTransfer
    {
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();

        $content = [
            $this->tester::KEY_DATA => [
                [
                    $this->tester::TABLE_ALIAS_COLUMN => $this->tester::BAR_TABLE_ALIAS,
                    $this->tester::TABLE_NAME_COLUMN => $this->tester::BAR_TABLE_NAME,
                    $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_CREATED_VALUE,
                ],
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        if ($isTransactionalHeaderValue !== null) {
            $isTransactionalHeader = strtolower($this->factory->getConfig()->getTransactionalHeader());

            $meta = $glueRequestTransfer->getMeta();
            $meta[$isTransactionalHeader] = [$isTransactionalHeaderValue];

            $glueRequestTransfer->setMeta($meta);
        }

        return $glueRequestTransfer;
    }
}
