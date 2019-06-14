<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Business\Mapping;

use Spryker\Zed\Cms\Business\Mapping\CmsGlossaryReader;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use SprykerTest\Zed\Cms\Business\CmsMocks;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Business
 * @group Mapping
 * @group CmsGlossaryReaderTest
 * Add your own group annotations below this line
 */
class CmsGlossaryReaderTest extends CmsMocks
{
    /**
     * @return void
     */
    public function testGetPageGlossaryAttributesShouldReadDataFromTemplateAndPersistenceToTransfer()
    {
        $localeFacadeMock = $this->createLocaleMock();

        $localeFacadeMock->method('getAvailableLocales')
            ->willReturn($this->getAvailableLocales());

        $cmsGlossaryReaderMock = $this->createCmsGlossaryReaderMock(null, $localeFacadeMock);

        $cmsPageEntityMock = $this->createCmsPageEntityMock();
        $cmsPageEntityMock->setVirtualColumn(CmsQueryContainer::TEMPLATE_PATH, 'test_template');

        $cmsGlossaryReaderMock->expects($this->once())
            ->method('getCmsPageEntity')
            ->willReturn($cmsPageEntityMock);

        $cmsGlossaryReaderMock->expects($this->any())
            ->method('fileExists')
            ->willReturn(true);

        $cmsGlossaryReaderMock->expects($this->once())
            ->method('readTemplateContents')
            ->willReturn($this->getSampleTemplateContents());

        $glossaryMappingCollection = $this->createGlossaryMappingCollection();

        $cmsGlossaryReaderMock->expects($this->once())
            ->method('getGlossaryMappingCollection')
            ->willReturn($glossaryMappingCollection);

        $cmsGlossaryTransfer = $cmsGlossaryReaderMock->findPageGlossaryAttributes(1);

        $cmsGlossaryAttributeTransfer = $cmsGlossaryTransfer->getGlossaryAttributes()[0];
        $this->assertEquals('title', $cmsGlossaryAttributeTransfer->getPlaceholder());
        $this->assertCount(2, $cmsGlossaryAttributeTransfer->getTranslations());

        $cmsGlossaryAttributeTransfer = $cmsGlossaryTransfer->getGlossaryAttributes()[1];
        $this->assertEquals('content', $cmsGlossaryAttributeTransfer->getPlaceholder());
        $this->assertCount(2, $cmsGlossaryAttributeTransfer->getTranslations());
    }

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface|null $cmsQueryContainerMock
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface|null $localeFacadeMock
     * @param \Spryker\Zed\Cms\CmsConfig|null $cmsConfigMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cms\Business\Mapping\CmsGlossaryReader
     */
    protected function createCmsGlossaryReaderMock(
        ?CmsQueryContainerInterface $cmsQueryContainerMock = null,
        ?CmsToLocaleFacadeInterface $localeFacadeMock = null,
        ?CmsConfig $cmsConfigMock = null
    ) {
        if ($cmsQueryContainerMock === null) {
            $cmsQueryContainerMock = $this->createCmsQueryContainerMock();
        }

        if ($localeFacadeMock === null) {
            $localeFacadeMock = $this->createLocaleMock();
        }

        if ($cmsConfigMock === null) {
            $cmsConfigMock = $this->createCmsConfigMock();
            $cmsConfigMock->method('getTemplateRealPaths')
                ->willReturn(['test_template']);
        }

        $cmsConfigMock
            ->method('getPlaceholderPattern')
            ->willReturn('/<!-- CMS_PLACEHOLDER : "[a-zA-Z0-9._-]*" -->/');

        $cmsConfigMock
            ->method('getPlaceholderValuePattern')
            ->willReturn('/"([^"]+)"/');

        return $this->getMockBuilder(CmsGlossaryReader::class)
            ->setConstructorArgs([$cmsQueryContainerMock, $localeFacadeMock, $cmsConfigMock])
            ->setMethods([
                'getCmsPageEntity',
                'getGlossaryMappingCollection',
                'readTemplateContents',
                'fileExists',
            ])
            ->getMock();
    }

    /**
     * @return string
     */
    protected function getSampleTemplateContents()
    {
        return '
            <html>
            <body>
            <title>Sample template</title>
            <!-- CMS_PLACEHOLDER : "title" -->
            <!-- CMS_PLACEHOLDER : "content" -->
            </body>
            </html>
        ';
    }

    /**
     * @return array
     */
    protected function createGlossaryMappingCollection()
    {
        $glossaryMappingCollection = [];
        $glossaryMappingEntity = $this->createGlossaryMappingEntityMock();
        $glossaryMappingEntity->setPlaceholder('title');

        $glossaryKeyEntityMock = $this->createGlossaryKeyEntityMock();
        $glossaryTranslationEntity = $this->createGlossaryTranslationEntityMock();
        $glossaryTranslationEntity->setFkLocale(1);
        $glossaryTranslationEntity->setValue('translated value');
        $glossaryKeyEntityMock->addSpyGlossaryTranslation($glossaryTranslationEntity);

        $glossaryMappingEntity->setGlossaryKey($glossaryKeyEntityMock);

        $glossaryMappingCollection[] = $glossaryMappingEntity;

        $glossaryMappingEntity = $this->createGlossaryMappingEntityMock();
        $glossaryMappingEntity->setPlaceholder('content');

        $glossaryKeyEntityMock = $this->createGlossaryKeyEntityMock();
        $glossaryTranslationEntity = $this->createGlossaryTranslationEntityMock();
        $glossaryTranslationEntity->setValue('translated value');
        $glossaryTranslationEntity->setFkLocale(1);
        $glossaryKeyEntityMock->addSpyGlossaryTranslation($glossaryTranslationEntity);

        $glossaryMappingEntity->setGlossaryKey($glossaryKeyEntityMock);

        $glossaryMappingCollection[] = $glossaryMappingEntity;

        return $glossaryMappingCollection;
    }

    /**
     * @return array
     */
    protected function getAvailableLocales()
    {
        return [
            1 => 'en_US',
            2 => 'de_DE',
        ];
    }
}
