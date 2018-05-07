<?php

namespace SprykerTest\Service\CodeItNow;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\CodeItNow\CodeItNowServiceInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group CodeItNow
 * @group CodeItNowServiceTest
 * Add your own group annotations below this line
 *
 * @group Barcode
 * @property \SprykerTest\Service\CodeItNow\CodeItNowServiceTester $tester
 */
class CodeItNowServiceTest extends Test
{
    const SOME_STRING = 'some string';
    const STANDARD_ENCODING = 'data:image/png;base64';

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testCode128BarcodeGeneration()
    {
        $barcodeResponseTransfer = $this->getService()
            ->generateCode128Barcode(static::SOME_STRING);

        $this->assertInstanceOf(BarcodeResponseTransfer::class, $barcodeResponseTransfer);
        $this->assertSame(static::STANDARD_ENCODING, $barcodeResponseTransfer->getEncoding());
    }

    /**
     * @return \Spryker\Service\CodeItNow\CodeItNowServiceInterface
     */
    protected function getService(): CodeItNowServiceInterface
    {
        return $this
            ->tester
            ->getLocator()
            ->codeItNow()
            ->service();
    }
}
