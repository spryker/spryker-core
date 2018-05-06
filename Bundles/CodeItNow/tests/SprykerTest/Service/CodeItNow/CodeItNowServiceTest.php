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
    const STRING_BARCODE = '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';
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
        $this->assertSame(static::STRING_BARCODE, $barcodeResponseTransfer->getCode());
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
