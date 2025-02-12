<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\TaxAppRestApi\Controller;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestTaxAppValidationAttributesTransfer;
use Generated\Shared\Transfer\TaxAppValidationRequestTransfer;
use Generated\Shared\Transfer\TaxAppValidationResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\TaxAppRestApi\Controller\TaxIdValidationController;
use Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToTaxAppClientInterface;
use Spryker\Glue\TaxAppRestApi\TaxAppRestApiDependencyProvider;
use SprykerTest\Glue\TaxAppRestApi\TaxAppRestApiTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group TaxAppRestApi
 * @group Controller
 * @group TaxIdValidationControllerTest
 * Add your own group annotations below this line
 */
class TaxIdValidationControllerTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @var \SprykerTest\Glue\TaxAppRestApi\TaxAppRestApiTester
     */
    protected TaxAppRestApiTester $tester;

    /**
     * @return void
     */
    public function _before(): void
    {
        parent::_before();

        $this->tester->getContainer()->set(static::SERVICE_RESOURCE_BUILDER, new RestResourceBuilder());
    }

    /**
     * @return void
     */
    public function testPostValidateTaxIdWhenRequestIsValidReturnsSuccessfulResponse(): void
    {
        // Arrange
        $restTaxAppValidationAttributesTransfer = $this->tester->createRestTaxAppValidationAttributesTransfer();

        $taxAppClientMock = $this->getMockBuilder(TaxAppRestApiToTaxAppClientInterface::class)->getMock();
        $restRequestMock = Stub::makeEmpty(RestRequestInterface::class);
        $taxAppClientMock
            ->method('validateTaxId')
            ->with($this->callback(function (TaxAppValidationRequestTransfer $taxAppValidationRequestTransfer) use ($restTaxAppValidationAttributesTransfer) {
                $this->assertSame($taxAppValidationRequestTransfer->getTaxId(), $restTaxAppValidationAttributesTransfer->getTaxId());
                $this->assertSame($taxAppValidationRequestTransfer->getCountryCode(), $taxAppValidationRequestTransfer->getCountryCode());

                return true;
            }))
            ->willReturn(
                (new TaxAppValidationResponseTransfer())
                ->setIsValid(true),
            );

        $this->tester->setDependency(TaxAppRestApiDependencyProvider::CLIENT_TAX_APP, $taxAppClientMock);

        // Act
        $restResponse = (new TaxIdValidationController())->postAction($restRequestMock, $restTaxAppValidationAttributesTransfer);

        //Assert
        $this->assertCount(0, $restResponse->getErrors());
        $this->assertSame(Response::HTTP_OK, $restResponse->getStatus());
    }

    /**
     * @return void
     */
    public function testGivenAMalformedRequestWhenTheTaxIdValidationApiIsCalledThenTheErrorMessageIsReturnedInTheResponse(): void
    {
        // Arrange
        $restTaxAppValidationAttributesTransfer = (new RestTaxAppValidationAttributesTransfer())->setTaxId('test')->setCountryCode('DE');

        $taxAppClientMock = $this->getMockBuilder(TaxAppRestApiToTaxAppClientInterface::class)->getMock();
        $restRequestMock = Stub::makeEmpty(RestRequestInterface::class);
        $taxAppClientMock
            ->method('validateTaxId')
            ->willReturn(
                (new TaxAppValidationResponseTransfer())
                    ->setIsValid(false)
                    ->setMessage('error'),
            );

        $this->tester->setDependency(TaxAppRestApiDependencyProvider::CLIENT_TAX_APP, $taxAppClientMock);

        // Act
        $restResponse = (new TaxIdValidationController())->postAction($restRequestMock, $restTaxAppValidationAttributesTransfer);

        //Assert
        $this->assertCount(1, $restResponse->getErrors());
        $this->assertSame('error', $restResponse->getErrors()[0]->getDetail());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $restResponse->getStatus());
    }
}
