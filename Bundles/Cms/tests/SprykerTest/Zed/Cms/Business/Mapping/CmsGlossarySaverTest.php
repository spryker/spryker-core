<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Business\Mapping;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Spryker\Zed\Cms\Business\Mapping\CmsGlossaryKeyGeneratorInterface;
use Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaver;
use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use SprykerTest\Zed\Cms\Business\CmsMocks;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Business
 * @group Mapping
 * @group CmsGlossarySaverTest
 * Add your own group annotations below this line
 */
class CmsGlossarySaverTest extends CmsMocks
{
    /**
     * @return void
     */
    public function testSaveCmsGlossaryShouldPersistGivenTransfer()
    {
        $cmsGlossarySaverMock = $this->createCmsGlossarySaverMock();

        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer();

        $glossaryEntityMock = $this->createGlossaryKeyEntityMock();
        $glossaryEntityMock->setIdGlossaryKey(1);

        $cmsGlossarySaverMock->expects($this->once())
            ->method('findGlossaryKeyEntityByTranslationKey')
            ->willReturn($glossaryEntityMock);

        $cmsGlossarySaverMock->expects($this->once())
            ->method('hasPagePlaceholderMapping')
            ->willReturn(false);

        $cmsGlossaryKeyMappingEntity = $this->createGlossaryMappingEntityMock();
        $cmsGlossaryKeyMappingEntity->setIdCmsGlossaryKeyMapping(1);

        $cmsGlossarySaverMock->expects($this->once())
            ->method('createCmsGlossaryKeyMappingEntity')
            ->willReturn($cmsGlossaryKeyMappingEntity);

        $cmsGlossaryTransfer = $cmsGlossarySaverMock->saveCmsGlossary($cmsGlossaryTransfer);

        $updatedCmsGlossaryAttributeTransfer = $cmsGlossaryTransfer->getGlossaryAttributes()[0];

        $this->assertSame(1, $updatedCmsGlossaryAttributeTransfer->getFkCmsGlossaryMapping());
        $this->assertSame(1, $updatedCmsGlossaryAttributeTransfer->getFkGlossaryKey());
        $this->assertCount(1, $updatedCmsGlossaryAttributeTransfer->getTranslations());
    }

    /**
     * @return void
     */
    public function testSaveCmsGlossaryShouldPersistGivenTransferWhenUpdatingExisting()
    {
        $cmsGlossarySaverMock = $this->createCmsGlossarySaverMock();

        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer();
        $cmsGlossaryAttributeTransfer = $cmsGlossaryTransfer->getGlossaryAttributes()[0];
        $cmsGlossaryAttributeTransfer->setFkCmsGlossaryMapping(1);
        $cmsGlossaryAttributeTransfer->setPlaceholder('new_placeholder');

        $glossaryEntityMock = $this->createGlossaryKeyEntityMock();
        $glossaryEntityMock->setIdGlossaryKey(1);

        $cmsGlossarySaverMock->expects($this->once())
            ->method('findGlossaryKeyEntityByTranslationKey')
            ->willReturn($glossaryEntityMock);

        $cmsGlossarySaverMock->expects($this->once())
            ->method('hasPagePlaceholderMapping')
            ->willReturn(false);

        $cmsGlossaryKeyMappingEntity = $this->createGlossaryMappingEntityMock();
        $cmsGlossaryKeyMappingEntity->setIdCmsGlossaryKeyMapping(1);
        $cmsGlossaryKeyMappingEntity->setPlaceholder('old_placeholder');

        $cmsGlossarySaverMock->expects($this->once())
            ->method('findGlossaryKeyMappingEntityById')
            ->willReturn($cmsGlossaryKeyMappingEntity);

        $cmsGlossaryTransfer = $cmsGlossarySaverMock->saveCmsGlossary($cmsGlossaryTransfer);

        $updatedCmsGlossaryAttributeTransfer = $cmsGlossaryTransfer->getGlossaryAttributes()[0];

        $this->assertSame(1, $updatedCmsGlossaryAttributeTransfer->getFkCmsGlossaryMapping());
        $this->assertSame(1, $updatedCmsGlossaryAttributeTransfer->getFkGlossaryKey());
        $this->assertCount(1, $updatedCmsGlossaryAttributeTransfer->getTranslations());
        $this->assertEquals('new_placeholder', $updatedCmsGlossaryAttributeTransfer->getPlaceholder());
    }

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface|null $cmsQueryContainerMock
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface|null $glossaryFacadeMock
     * @param \Spryker\Zed\Cms\Business\Mapping\CmsGlossaryKeyGeneratorInterface|null $cmsGlossaryKeyGeneratorMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaver
     */
    protected function createCmsGlossarySaverMock(
        ?CmsQueryContainerInterface $cmsQueryContainerMock = null,
        ?CmsToGlossaryInterface $glossaryFacadeMock = null,
        ?CmsGlossaryKeyGeneratorInterface $cmsGlossaryKeyGeneratorMock = null
    ) {

        if ($glossaryFacadeMock === null) {
            $glossaryFacadeMock = $this->createGlossaryFacadeMock();
        }

        if ($cmsQueryContainerMock === null) {
            $cmsQueryContainerMock = $this->createCmsQueryContainerMock();
        }

        if ($cmsGlossaryKeyGeneratorMock === null) {
            $cmsGlossaryKeyGeneratorMock = $this->createCmsGlossaryKeyGeneratorMock();
        }

        return $this->getMockBuilder(CmsGlossarySaver::class)
            ->setConstructorArgs([$cmsQueryContainerMock, $glossaryFacadeMock, $cmsGlossaryKeyGeneratorMock])
            ->setMethods([
                'findGlossaryKeyEntityByTranslationKey',
                'findGlossaryKeyMappingEntityById',
                'hasPagePlaceholderMapping',
                'createCmsGlossaryKeyMappingEntity',
            ])
            ->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    protected function createCmsGlossaryTransfer()
    {
        $cmsGlossaryTransfer = new CmsGlossaryTransfer();

        $cmsGlossaryAttributeTransfer = new CmsGlossaryAttributesTransfer();
        $cmsGlossaryAttributeTransfer->setPlaceholder('title');
        $cmsGlossaryAttributeTransfer->setTemplateName('template');

        $cmsPlaceholderTransfer = new CmsPlaceholderTranslationTransfer();
        $cmsPlaceholderTransfer->setFkLocale(1);
        $cmsPlaceholderTransfer->setLocaleName('en_US');
        $cmsPlaceholderTransfer->setTranslation('translated value');
        $cmsGlossaryAttributeTransfer->addTranslation($cmsPlaceholderTransfer);

        $cmsGlossaryTransfer->addGlossaryAttribute($cmsGlossaryAttributeTransfer);

        return $cmsGlossaryTransfer;
    }
}
