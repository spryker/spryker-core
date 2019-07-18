<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Business\Page;

use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Spryker\Zed\Cms\Business\Page\CmsPageActivator;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use SprykerTest\Zed\Cms\Business\CmsMocks;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Business
 * @group Page
 * @group CmsPageActivatorTest
 * Add your own group annotations below this line
 */
class CmsPageActivatorTest extends CmsMocks
{
    /**
     * @return void
     */
    public function testActivatePageShouldPersistActiveFlagAndTriggerTouch()
    {
        $cmsPageEntityMock = $this->createCmsPageEntityMock();
        $cmsPageEntityMock->expects($this->once())
            ->method('save');

        $touchFacadeMock = $this->createTouchFacadeMock();
        $touchFacadeMock->expects($this->once())
            ->method('touchActive');

        $cmsPageActivatorMock = $this->createCmsPageActivateMock($cmsPageEntityMock, null, $touchFacadeMock);

        $cmsPageActivatorMock->method('countNumberOfGlossaryKeysForIdCmsPage')->willReturn(5);

        $cmsPageEntityMock->setIdCmsPage(1);
        $cmsPageActivatorMock->activate(1);

        $this->assertTrue($cmsPageEntityMock->getIsActive());
    }

    /**
     * @return void
     */
    public function testDeActivatePageShouldPersistInActiveFlagAndTriggerTouch()
    {
        $cmsPageEntityMock = $this->createCmsPageEntityMock();
        $cmsPageEntityMock->expects($this->once())
            ->method('save');

        $touchFacadeMock = $this->createTouchFacadeMock();
        $touchFacadeMock->expects($this->once())
            ->method('touchActive');

        $cmsPageActivatorMock = $this->createCmsPageActivateMock($cmsPageEntityMock, null, $touchFacadeMock);

        $cmsPageEntityMock->setIdCmsPage(1);
        $cmsPageActivatorMock->deactivate(1);

        $this->assertFalse($cmsPageEntityMock->getIsActive());
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface|null $cmsQueryContainerMock
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface|null $touchFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cms\Business\Page\CmsPageActivator
     */
    protected function createCmsPageActivateMock(
        SpyCmsPage $cmsPageEntity,
        ?CmsQueryContainerInterface $cmsQueryContainerMock = null,
        ?CmsToTouchFacadeInterface $touchFacadeMock = null
    ) {

        if ($cmsQueryContainerMock === null) {
            $cmsQueryContainerMock = $this->createCmsQueryContainerMock();
        }

        if ($touchFacadeMock === null) {
            $touchFacadeMock = $this->createTouchFacadeMock();
        }

        $cmsPageActivatorMock = $this->getMockBuilder(CmsPageActivator::class)
            ->setMethods(['getCmsPageEntity', 'countNumberOfGlossaryKeysForIdCmsPage'])
            ->setConstructorArgs([$cmsQueryContainerMock, $touchFacadeMock, []])
            ->getMock();

        $cmsPageActivatorMock->method('getCmsPageEntity')
            ->willReturn($cmsPageEntity);

        return $cmsPageActivatorMock;
    }
}
