<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsGui\Communication\Form\Constraint;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Spryker\Zed\Cms\Business\CmsFacade;
use Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl;
use Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrlValidator;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsBridge;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToUrlBridge;
use Spryker\Zed\Url\Business\UrlFacade;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CmsGui
 * @group Communication
 * @group Form
 * @group Constraint
 * @group UniqueGlossaryUrlValidatorTest
 * Add your own group annotations below this line
 */
class UniqueGlossaryUrlValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testValidateWhenSameUrlEditedShouldNotRegisterError()
    {
        $uniqueUrlConstraint = $this->createUniqueUrlConstraint();
        $cmsPageTransfer = $this->createCmsPageTransfer();

        $cmsFacade = $this->createCmsFacade();

        $idCmsPage = $cmsFacade->createPage($cmsPageTransfer);
        $cmsPageTransfer->setFkPage($idCmsPage);

        $cmsPageAttributeTransfer = $cmsPageTransfer->getPageAttributes()[0];
        $cmsPageAttributeTransfer->setIdCmsPage($idCmsPage);

        $executionContextMock = $this->createExecutionContextMock();

        $executionContextMock
            ->expects($this->never())
            ->method('buildViolation');

        $this->validateCmsAttributeTransfer($executionContextMock, $cmsPageAttributeTransfer, $uniqueUrlConstraint);
    }

    /**
     * @return void
     */
    public function testValidateWhenRedirectUrlUsedShouldNotRegisterError()
    {
        $uniqueUrlConstraint = $this->createUniqueUrlConstraint();
        $cmsPageTransfer = $this->createCmsPageTransfer();

        $cmsFacade = $this->createCmsFacade();

        $idCmsPage = $cmsFacade->createPage($cmsPageTransfer);
        $cmsPageTransfer->setFkPage($idCmsPage);

        $cmsPageAttributeTransfer = $cmsPageTransfer->getPageAttributes()[0];
        $cmsPageAttributeTransfer->setIdCmsPage($idCmsPage);

        $oldUrl = $cmsPageAttributeTransfer->getUrl();
        $executionContextMock = $this->createExecutionContextMock();

        $executionContextMock
            ->expects($this->never())
            ->method('buildViolation');

        $cmsPageAttributeTransfer->setUrl('new-test-cms-validation-url');
        $this->validateCmsAttributeTransfer($executionContextMock, $cmsPageAttributeTransfer, $uniqueUrlConstraint);
        $cmsFacade->updatePage($cmsPageTransfer);

        $cmsPageAttributeTransfer->setUrl($oldUrl);
        $this->validateCmsAttributeTransfer($executionContextMock, $cmsPageAttributeTransfer, $uniqueUrlConstraint);
        $cmsFacade->updatePage($cmsPageTransfer);
    }

    /**
     * @return void
     */
    public function testValidateWhenExistingUrlUsedShouldRegisterError()
    {
        $uniqueUrlConstraint = $this->createUniqueUrlConstraint();
        $cmsPageTransfer = $this->createCmsPageTransfer();

        $cmsFacade = $this->createCmsFacade();

        $idCmsPage = $cmsFacade->createPage($cmsPageTransfer);
        $cmsPageTransfer->setFkPage($idCmsPage);

        $cmsPageAttributeTransfer = $cmsPageTransfer->getPageAttributes()[0];
        $cmsPageAttributeTransfer->setIdCmsPage($idCmsPage);

        $executionContextMock = $this->createExecutionContextMock();

        $executionContextMock
            ->expects($this->once())
            ->method('buildViolation');

        $cmsPageAttributeTransfer->setIdCmsPage(123);
        $this->validateCmsAttributeTransfer($executionContextMock, $cmsPageAttributeTransfer, $uniqueUrlConstraint);
        $cmsFacade->updatePage($cmsPageTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Validator\Context\ExecutionContextInterface
     */
    protected function createExecutionContextMock()
    {
        $executionContextMock = $this->getMockBuilder(ExecutionContextInterface::class)
             ->getMock();

        $executionContextMock->method('buildViolation')
            ->willReturn($this->createConstraintViolationBuilderMock());

        return $executionContextMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface
     */
    protected function createConstraintViolationBuilderMock()
    {
         $constraintViolationBuilderMock = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)
             ->getMock();

        $constraintViolationBuilderMock->method('atPath')->willReturnSelf();

        return $constraintViolationBuilderMock;
    }

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface
     */
    protected function createCmsFacade()
    {
        return new CmsGuiToCmsBridge(new CmsFacade());
    }

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToUrlInterface
     */
    protected function createUrlFacade()
    {
        return new CmsGuiToUrlBridge(new UrlFacade());
    }

    /**
     * @return \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl
     */
    protected function createUniqueUrlConstraint()
    {
        $uniqueUrlConstraint = new UniqueUrl([
            UniqueUrl::OPTION_CMS_FACADE => $this->createCmsFacade(),
            UniqueUrl::OPTION_URL_FACADE => $this->createUrlFacade(),
        ]);

        return $uniqueUrlConstraint;
    }

    /**
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer
     */
    protected function createCmsAttributeTransfer()
    {
        $cmsPageAttributeTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributeTransfer->setUrl('test-' . rand() . '-validator-' . rand());
        $cmsPageAttributeTransfer->setFkLocale(66);
        $cmsPageAttributeTransfer->setName('test-url-validator');
        $cmsPageAttributeTransfer->setLocaleName('en_US');
        $cmsPageAttributeTransfer->setUrlPrefix('en');

        return $cmsPageAttributeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function createCmsPageTransfer()
    {
        $cmsPageTransfer = new CmsPageTransfer();
        $cmsPageTransfer->setFkTemplate(1);
        $cmsPageTransfer->setTemplateName('test template for validator');

        $cmsPageAttributeTransfer = $this->createCmsAttributeTransfer();
        $cmsPageTransfer->addPageAttribute($cmsPageAttributeTransfer);

        return $cmsPageTransfer;
    }

    /**
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $executionContextMock
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributeTransfer
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl $uniqueUrlConstraint
     *
     * @return void
     */
    protected function validateCmsAttributeTransfer(
        ExecutionContextInterface $executionContextMock,
        CmsPageAttributesTransfer $cmsPageAttributeTransfer,
        UniqueUrl $uniqueUrlConstraint
    ) {
        $uniqueUrlValidator = new UniqueUrlValidator();
        $uniqueUrlValidator->initialize($executionContextMock);
        $uniqueUrlValidator->validate($cmsPageAttributeTransfer, $uniqueUrlConstraint);
    }
}
