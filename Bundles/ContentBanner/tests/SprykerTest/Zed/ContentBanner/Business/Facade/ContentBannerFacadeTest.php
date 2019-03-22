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
 *
 * @group SprykerTest
 * @group Zed
 * @group ContentBanner
 * @group Business
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
        $validationResult = $this->tester->getFacade()->validateContentBanner($contentBannerTransfer);

        $this->assertTrue($validationResult->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateContentBannerValidationFails(): void
    {
        $contentBannerTransfer = (new ContentBannerTransfer())
            ->setTitle('Very long text string Lorem ipsum dolor sit amet, consectetur adipiscing elit,
             sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
              quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
               Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit
                 anim id est laborum.')
            ->setAltText('SampleTest')
            ->setClickUrl('http://some.url')
            ->setImageUrl('http://image.url')
            ->setSubtitle('subtitle text');
        $validationResult = $this->tester->getFacade()->validateContentBanner($contentBannerTransfer);

        $this->assertFalse($validationResult->getIsSuccess());
    }
}
