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
use Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface;
use Spryker\Zed\Cms\Business\Template\TemplateManager;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface
     */
    protected function createTouchFacadeMock()
    {
        return $this->getMockBuilder(CmsToTouchInterface::class)
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface
     */
    protected function createUrlFacadeMock()
    {
        return $this->getMockBuilder(CmsToUrlInterface::class)
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface
     */
    protected function createLocaleMock()
    {
        return $this->getMockBuilder(CmsToLocaleInterface::class)
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface
     */
    protected function createGlossaryFacadeMock()
    {
        return $this->getMockBuilder(CmsToGlossaryInterface::class)
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
}
