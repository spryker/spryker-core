<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilder;
use Spryker\Zed\Cms\CmsConfig;
use SprykerTest\Zed\Cms\Business\CmsMocks;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Business
 * @group Page
 * @group CmsPageUrlBuilderTest
 * Add your own group annotations below this line
 */
class CmsPageUrlBuilderTest extends CmsMocks
{
    /**
     * @return void
     */
    public function testBuildPageUrlWithPrefixInUrl()
    {
        $cmsUrlBuilder = $this->createCmsUrlBuilder();

        $cmsPageAttributesTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributesTransfer->setUrl('/en/url');
        $cmsPageAttributesTransfer->setUrlPrefix('/en/');
        $cmsPageAttributesTransfer->setLocaleName('en_US');

        $url = $cmsUrlBuilder->buildPageUrl($cmsPageAttributesTransfer);

        $this->assertEquals($cmsPageAttributesTransfer->getUrl(), $url);
    }

    /**
     * @return void
     */
    public function testBuildPageUrlWithoutPrefixInUrl()
    {
        $cmsUrlBuilder = $this->createCmsUrlBuilder();

        $cmsPageAttributesTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributesTransfer->setUrl('url');
        $cmsPageAttributesTransfer->setUrlPrefix('/en/');
        $cmsPageAttributesTransfer->setLocaleName('en_US');

        $url = $cmsUrlBuilder->buildPageUrl($cmsPageAttributesTransfer);

        $this->assertEquals($cmsPageAttributesTransfer->getUrlPrefix() . $cmsPageAttributesTransfer->getUrl(), $url);
    }

    /**
     * @return void
     */
    public function testGetPageUrlPrefixShouldBuildPrefixFromLanguageCode()
    {
        $cmsPageAttributeTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributeTransfer->setLocaleName('en_US');

        $cmsUrlBuilder = $this->createCmsUrlBuilder();
        $urlPrefix = $cmsUrlBuilder->getPageUrlPrefix($cmsPageAttributeTransfer);

        $this->assertEquals('/en/', $urlPrefix);
    }

    /**
     * @param \Spryker\Zed\Cms\CmsConfig|null $cmsConfigMock
     *
     * @return \Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilder
     */
    protected function createCmsUrlBuilder(?CmsConfig $cmsConfigMock = null)
    {
        if ($cmsConfigMock === null) {
            $cmsConfigMock = $this->createCmsConfigMock();
            $cmsConfigMock->method('appendPrefixToCmsPageUrl')
                ->willReturn(true);
        }

        return new CmsPageUrlBuilder($cmsConfigMock);
    }
}
