<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentBanner\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ContentBannerTermTransfer;

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
        $contentBannerTermTransfer = (new ContentBannerTermTransfer())
            ->setTitle('Test')
            ->setAltText('SampleTest')
            ->setClickUrl('http://some.url')
            ->setImageUrl('http://image.url')
            ->setSubtitle('subtitle text');
        $validationResult = $this->tester->getFacade()->validateContentBannerTerm($contentBannerTermTransfer);

        $this->assertTrue($validationResult->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateContentBannerWithLongTitleValidationFails(): void
    {
        $contentBannerTermTransfer = (new ContentBannerTermTransfer())
            ->setTitle('Very long text string Lorem ipsum dolor sit amet, consectetur adipiscing elit,
             sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')
            ->setAltText('SampleTest')
            ->setClickUrl('http://some.url')
            ->setImageUrl('http://image.url')
            ->setSubtitle('subtitle text');
        $validationResult = $this->tester->getFacade()->validateContentBannerTerm($contentBannerTermTransfer);

        $this->assertFalse($validationResult->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateContentBannerWithEmptySubtitleValidationFails(): void
    {
        $contentBannerTermTransfer = (new ContentBannerTermTransfer())
            ->setTitle('Sample text')
            ->setAltText('SampleTest')
            ->setClickUrl('http://some.url')
            ->setImageUrl('http://image.url')
            ->setSubtitle('');
        $validationResult = $this->tester->getFacade()->validateContentBannerTerm($contentBannerTermTransfer);

        $this->assertFalse($validationResult->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateContentBannerWithVeryLongSubtitleValidationFails(): void
    {
        $contentBannerTermTransfer = (new ContentBannerTermTransfer())
            ->setTitle('Sample text')
            ->setAltText('SampleTest')
            ->setClickUrl('http://some.url')
            ->setImageUrl('http://image.url')
            ->setSubtitle('Very long text string Lorem ipsum dolor sit amet, consectetur adipiscing elit,
             sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
        $validationResult = $this->tester->getFacade()->validateContentBannerTerm($contentBannerTermTransfer);

        $this->assertFalse($validationResult->getIsSuccess());
    }
}
