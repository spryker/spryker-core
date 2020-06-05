<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsContentWidgetContentItemConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsContentWidgetContentItemConnector
 * @group Business
 * @group Facade
 * @group CmsContentWidgetContentItemConnectorFacadeTest
 * Add your own group annotations below this line
 */
class CmsContentWidgetContentItemConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsContentWidgetContentItemConnector\CmsContentWidgetContentItemConnectorBusinessTester
     */
    protected $tester;

    protected const CONTENT_KEY = 'test-key';

    /**
     * @return void
     */
    public function testMapContentItemKeyListReturnsCorrectResponse(): void
    {
        // Arrange
        $this->tester->haveContent([
            ContentTransfer::KEY => static::CONTENT_KEY,
        ]);

        // Act
        $foundArray = $this->tester->getFacade()->mapContentItemKeys([static::CONTENT_KEY]);

        // Assert
        $this->assertNotEmpty($foundArray);
        $this->assertEquals(
            [static::CONTENT_KEY => static::CONTENT_KEY],
            $foundArray
        );
    }

    /**
     * @return void
     */
    public function testMapContentItemKeyListReturnsInCorrectResponse(): void
    {
        // Arrange
        $this->tester->haveContent([
            ContentTransfer::KEY => 'test-key-2',
        ]);

        // Act
        $foundArray = $this->tester->getFacade()->mapContentItemKeys([static::CONTENT_KEY]);

        // Assert
        $this->assertEmpty($foundArray);
    }
}
