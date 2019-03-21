<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentProduct\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ContentProductAbstractListTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Spryker\Zed\ContentProduct\ContentProductConfig;

/**
 * Auto-generated group annotations
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
     * @uses \Spryker\Zed\ContentProduct\Business\Validator\ContentProductAbstractListValidator::ERROR_MESSAGE_NUMBER_OF_PRODUCTS
     */
    protected const ERROR_MESSAGE_NUMBER_OF_PRODUCTS = 'Number of products is too long, max {count}, please check row with key:"{key}", column:"{column}"';

    /**
     * @var \SprykerTest\Zed\ContentProduct\ContentProductBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateContentProductAbstractList(): void
    {
        $products = [];

        for ($i = 1; $i <= ContentProductConfig::MAX_NUMBER_PRODUCTS_IN_PRODUCT_ABSTRACT_LIST; $i++) {
            $products[] = $i;
        }

        $contentProductAbstractListTransfer = (new ContentProductAbstractListTransfer())
            ->setIdProductAbstracts($products);

        $contentValidationResponseTransfer = $this->getFacade()->validateContentProductAbstractList($contentProductAbstractListTransfer);

        $this->assertInstanceOf(ContentValidationResponseTransfer::class, $contentValidationResponseTransfer);
        $this->assertTrue($contentValidationResponseTransfer->getIsSuccess());
        $this->assertCount(0, $contentValidationResponseTransfer->getParameterMessages());
    }

    /**
     * @return void
     */
    public function testValidateContentProductAbstractListFail(): void
    {
        $products = [];

        for ($i = 1; $i <= ContentProductConfig::MAX_NUMBER_PRODUCTS_IN_PRODUCT_ABSTRACT_LIST + 1; $i++) {
            $products[] = $i;
        }

        $contentProductAbstractListTransfer = (new ContentProductAbstractListTransfer())
            ->setIdProductAbstracts($products);

        $contentValidationResponseTransfer = $this->getFacade()->validateContentProductAbstractList($contentProductAbstractListTransfer);

        $this->assertInstanceOf(ContentValidationResponseTransfer::class, $contentValidationResponseTransfer);
        $this->assertFalse($contentValidationResponseTransfer->getIsSuccess());
        $messageTransfer = $contentValidationResponseTransfer->getParameterMessages()
            ->offsetGet(0)
            ->getMessages()
            ->offsetGet(0);
        $this->assertEquals(static::ERROR_MESSAGE_NUMBER_OF_PRODUCTS, $messageTransfer->getValue());
    }

    /**
     * @return \Spryker\Zed\ContentProduct\Business\ContentProductFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
