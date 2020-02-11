<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentProduct\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Spryker\Zed\ContentProduct\ContentProductConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ContentProduct
 * @group Business
 * @group Facade
 * @group Facade
 * @group ContentProductFacadeTest
 * Add your own group annotations below this line
 */
class ContentProductFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ContentProduct\ContentProductBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateContentProductAbstractListTermIsSuccessful(): void
    {
        $products = range(1, $this->getConfig()->getMaxProductsInProductAbstractList());

        $contentProductAbstractListTermTransfer = (new ContentProductAbstractListTermTransfer())
            ->setIdProductAbstracts($products);

        $contentValidationResponseTransfer = $this->tester->getFacade()->validateContentProductAbstractListTerm($contentProductAbstractListTermTransfer);

        $this->assertInstanceOf(ContentValidationResponseTransfer::class, $contentValidationResponseTransfer);
        $this->assertTrue($contentValidationResponseTransfer->getIsSuccess());
        $this->assertCount(0, $contentValidationResponseTransfer->getParameterMessages());
    }

    /**
     * @return void
     */
    public function testValidateContentProductAbstractListTermNumberOfProductsFail(): void
    {
        $products = range(1, $this->getConfig()->getMaxProductsInProductAbstractList() + 1);

        $contentProductAbstractListTermTransfer = (new ContentProductAbstractListTermTransfer())
            ->setIdProductAbstracts($products);

        $contentValidationResponseTransfer = $this->tester->getFacade()->validateContentProductAbstractListTerm($contentProductAbstractListTermTransfer);

        $this->assertInstanceOf(ContentValidationResponseTransfer::class, $contentValidationResponseTransfer);
        $this->assertFalse($contentValidationResponseTransfer->getIsSuccess());
        $parameter = $contentValidationResponseTransfer->getParameterMessages()
            ->offsetGet(0)
            ->getParameter();
        $this->assertEquals($parameter, ContentProductAbstractListTermTransfer::ID_PRODUCT_ABSTRACTS);
    }

    /**
     * @return \Spryker\Zed\ContentProduct\ContentProductConfig
     */
    protected function getConfig(): ContentProductConfig
    {
        return new ContentProductConfig();
    }
}
