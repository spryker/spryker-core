<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsContentWidgetContentConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsContentWidgetContentConnector
 * @group Business
 * @group Facade
 * @group CmsContentWidgetContentConnectorFacadeTest
 * Add your own group annotations below this line
 */
class CmsContentWidgetContentConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsContentWidgetContentConnector\CmsContentWidgetContentConnectorBusinessTester
     */
    protected $tester;

    protected const CONTENT_KEY = 'test-key';

    /**
     * @return void
     */
    public function testMapContentItemKeysReturnsCorrectResponse(): void
    {
        // Arrange
        $this->tester->haveContent([
            ContentTransfer::KEY => static::CONTENT_KEY,
        ]);

        // Act
        $mappedKeys = $this->tester->getFacade()->mapContentItemKeys([static::CONTENT_KEY]);

        // Assert
        $this->assertNotEmpty($mappedKeys);
        $this->assertEquals(
            [static::CONTENT_KEY => static::CONTENT_KEY],
            $mappedKeys
        );
    }

    /**
     * @return void
     */
    public function testMapContentItemKeysForNotExistingKeyReturnsEmptyResult(): void
    {
        // Arrange
        $this->tester->haveContent([
            ContentTransfer::KEY => 'test-key-2',
        ]);

        // Act
        $mappedKeys = $this->tester->getFacade()->mapContentItemKeys([static::CONTENT_KEY]);

        // Assert
        $this->assertEmpty($mappedKeys);
    }
}
