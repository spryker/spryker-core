<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentBanner\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ContentBannerTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ContentBanner
 * @group Business
 * @group Facade
 * @group Facade
 * @group ContentBannerFacadeTest
 * Add your own group annotations below this line
 */
class ContentBannerFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ContentBanner\ContentBannerBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateContentBannerValidationSuccessful(): void
    {
        $contentBannerTransfer = (new ContentBannerTransfer())
            ->setTitle('Test')
            ->setAltText('SampleTest')
            ->setClickUrl('http://some.url')
            ->setImageUrl('http://image.url')
            ->setSubtitle('subtitle text');
        $validationResult = $this->tester->getFacade()->validateContentBannerTerm($contentBannerTransfer);

        $this->assertTrue($validationResult->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateContentBannerWithLongTitleValidationFails(): void
    {
        $contentBannerTransfer = (new ContentBannerTransfer())
            ->setTitle('Very long text string Lorem ipsum dolor sit amet, consectetur adipiscing elit,
             sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')
            ->setAltText('SampleTest')
            ->setClickUrl('http://some.url')
            ->setImageUrl('http://image.url')
            ->setSubtitle('subtitle text');
        $validationResult = $this->tester->getFacade()->validateContentBannerTerm($contentBannerTransfer);

        $this->assertFalse($validationResult->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateContentBannerWithInvalidClickUrlValidationFails(): void
    {
        $contentBannerTransfer = (new ContentBannerTransfer())
            ->setTitle('Sample text')
            ->setAltText('SampleTest')
            ->setClickUrl('invalid url')
            ->setImageUrl('http://image.url')
            ->setSubtitle('subtitle text');
        $validationResult = $this->tester->getFacade()->validateContentBannerTerm($contentBannerTransfer);

        $this->assertFalse($validationResult->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateContentBannerWithEmptySubtitleValidationFails(): void
    {
        $contentBannerTransfer = (new ContentBannerTransfer())
            ->setTitle('Sample text')
            ->setAltText('SampleTest')
            ->setClickUrl('http://some.url')
            ->setImageUrl('http://image.url')
            ->setSubtitle('');
        $validationResult = $this->tester->getFacade()->validateContentBannerTerm($contentBannerTransfer);

        $this->assertFalse($validationResult->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateContentBannerWithVeryLongSubtitleValidationFails(): void
    {
        $contentBannerTransfer = (new ContentBannerTransfer())
            ->setTitle('Sample text')
            ->setAltText('SampleTest')
            ->setClickUrl('http://some.url')
            ->setImageUrl('http://image.url')
            ->setSubtitle('Very long text string Lorem ipsum dolor sit amet, consectetur adipiscing elit,
             sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
        $validationResult = $this->tester->getFacade()->validateContentBannerTerm($contentBannerTransfer);

        $this->assertFalse($validationResult->getIsSuccess());
    }
}
