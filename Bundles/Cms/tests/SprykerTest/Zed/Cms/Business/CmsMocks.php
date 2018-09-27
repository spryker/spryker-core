<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Business;

use Codeception\Test\Unit;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKey;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Cms\Business\Mapping\CmsGlossaryKeyGeneratorInterface;
use Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface;
use Spryker\Zed\Cms\Business\Page\CmsPageMapper;
use Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface;
use Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationMapper;
use Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationMapperInterface;
use Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationWriterInterface;
use Spryker\Zed\Cms\Business\Template\TemplateManager;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryFacadeInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

abstract class CmsMocks extends Unit
{
    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $propelConnectionMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected function createCmsQueryContainerMock(?ConnectionInterface $propelConnectionMock = null)
    {
        $cmsQueryContainerMock = $this->getMockBuilder(CmsQueryContainerInterface::class)
            ->getMock();

        if ($propelConnectionMock === null) {
            $propelConnectionMock = $this->createPropelConnectionMock();
        }

        $cmsQueryContainerMock->method('getConnection')
            ->willReturn($propelConnectionMock);

        return $cmsQueryContainerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Propel\Runtime\Connection\ConnectionInterface
     */
    protected function createPropelConnectionMock()
    {
        return $this->getMockBuilder(ConnectionInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface
     */
    protected function createTouchFacadeMock()
    {
        return $this->getMockBuilder(CmsToTouchFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    protected function createCmsPageEntityMock()
    {
        return $this->getMockBuilder(SpyCmsPage::class)
            ->setMethods(['save'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes
     */
    protected function createCmsPageLocalizedAttributesEntityMock()
    {
        return $this->getMockBuilder(SpyCmsPageLocalizedAttributes::class)
            ->setMethods(['save'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Dependency\Facade\CmsToUrlFacadeInterface
     */
    protected function createUrlFacadeMock()
    {
        return $this->getMockBuilder(CmsToUrlFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface
     */
    protected function createCmsPageUrlBuilderMock()
    {
        return $this->getMockBuilder(CmsPageUrlBuilderInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface
     */
    protected function createCmsGlossarySaverMock()
    {
        return $this->getMockBuilder(CmsGlossarySaverInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Business\Template\TemplateManagerInterface
     */
    protected function createTemplateManagerMock()
    {
        return $this->getMockBuilder(TemplateManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTemplateById'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\CmsConfig
     */
    protected function createCmsConfigMock()
    {
        return $this->getMockBuilder(CmsConfig::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface
     */
    protected function createLocaleMock()
    {
        return $this->getMockBuilder(CmsToLocaleFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping
     */
    protected function createGlossaryMappingEntityMock()
    {
        return $this->getMockBuilder(SpyCmsGlossaryKeyMapping::class)
            ->setMethods(['save'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Glossary\Persistence\SpyGlossaryKey
     */
    protected function createGlossaryKeyEntityMock()
    {
        return $this->getMockBuilder(SpyGlossaryKey::class)
            ->setMethods(['save'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation
     */
    protected function createGlossaryTranslationEntityMock()
    {
        return $this->getMockBuilder(SpyGlossaryTranslation::class)
            ->setMethods(['save'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryFacadeInterface
     */
    protected function createGlossaryFacadeMock()
    {
        return $this->getMockBuilder(CmsToGlossaryFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Business\Mapping\CmsGlossaryKeyGeneratorInterface
     */
    protected function createCmsGlossaryKeyGeneratorMock()
    {
        return $this->getMockBuilder(CmsGlossaryKeyGeneratorInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationWriterInterface
     */
    protected function createCmsPageStoreRelationWriterMock()
    {
        return $this->getMockBuilder(CmsPageStoreRelationWriterInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationMapperInterface
     */
    protected function createCmsPageStoreRelationMapperMock()
    {
        return $this->getMockBuilder(CmsPageStoreRelationMapper::class)
            ->getMock();
    }

    /**
     * @param \Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface|null $cmsPageUrlBuilderMock |\PHPUnit\Framework\MockObject\MockObject
     * @param \Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationMapperInterface|null $cmsPageStoreRelationMapperMock |\PHPUnit\Framework\MockObject\MockObject
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cms\Business\Page\CmsPageMapperInterface
     */
    protected function createCmsPageMapperMock(?CmsPageUrlBuilderInterface $cmsPageUrlBuilderMock = null, ?CmsPageStoreRelationMapperInterface $cmsPageStoreRelationMapperMock = null)
    {
        if ($cmsPageUrlBuilderMock === null) {
            $cmsPageUrlBuilderMock = $this->createCmsPageUrlBuilderMock();
        }

        if ($cmsPageStoreRelationMapperMock === null) {
            $cmsPageStoreRelationMapperMock = $this->createCmsPageStoreRelationMapperMock();
        }

        return $this->getMockBuilder(CmsPageMapper::class)
            ->setConstructorArgs([$cmsPageUrlBuilderMock, $cmsPageStoreRelationMapperMock])
            ->setMethods(null)
            ->getMock();
    }
}
