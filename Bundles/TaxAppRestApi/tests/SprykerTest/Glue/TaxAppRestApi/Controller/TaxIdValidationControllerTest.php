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
use Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\TaxAppRestApi\Controller\TaxIdValidationController;
use Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToGlossaryStorageClientInterface;
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
     * @var string
     */
    protected const GLOSSARY_KEY_TAX_ID_INVALID = 'validation.error.tax_id_invalid';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_TAX_ID_FORMAT_INVALID = 'tax_app.vertex.validation.error.tax_id_format_invalid';

    /**
     * @var string
     */
    protected const GLOSSARY_SUFFIX_VERTEX = 'tax_app.vertex';

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

    /**
     * @dataProvider glossaryMessageDataProvider
     *
     * @param string $acceptLanguage
     * @param \Generated\Shared\Transfer\RestTaxAppValidationAttributesTransfer $restTaxAppValidationAttributesTransfer
     * @param \Generated\Shared\Transfer\TaxAppValidationResponseTransfer $taxAppValidationResponseTransfer
     * @param array<string, string> $glossaryTranslations
     * @param string $expectedMessage
     *
     * @return void
     */
    public function testPostValidateTaxIdWithDifferentLocalesAndGlossaryKeys(
        string $acceptLanguage,
        RestTaxAppValidationAttributesTransfer $restTaxAppValidationAttributesTransfer,
        TaxAppValidationResponseTransfer $taxAppValidationResponseTransfer,
        array $glossaryTranslations,
        string $expectedMessage
    ): void {
        // Arrange
        $taxAppClientMock = $this->getMockBuilder(TaxAppRestApiToTaxAppClientInterface::class)->getMock();
        $glossaryStorageClientMock = $this->getMockBuilder(TaxAppRestApiToGlossaryStorageClientInterface::class)->getMock();

        // Create metadata mock with the accept language
        $metadataMock = $this->getMockBuilder(MetadataInterface::class)->getMock();
        $metadataMock->method('getLocale')->willReturn($acceptLanguage);

        // Create REST request mock with metadata
        $restRequestMock = $this->getMockBuilder(RestRequestInterface::class)->getMock();
        $restRequestMock->method('getMetadata')->willReturn($metadataMock);

        $taxAppClientMock
            ->method('validateTaxId')
            ->willReturn($taxAppValidationResponseTransfer);

        $glossaryStorageClientMock
            ->method('translate')
            ->willReturnCallback(function (string $key, string $locale) use ($glossaryTranslations) {
                $lookupKey = $key . '.' . $locale;

                return $glossaryTranslations[$lookupKey] ?? $key;
            });

        $this->tester->setDependency(TaxAppRestApiDependencyProvider::CLIENT_TAX_APP, $taxAppClientMock);
        $this->tester->setDependency(TaxAppRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE, $glossaryStorageClientMock);

        // Act
        $restResponse = (new TaxIdValidationController())->postAction($restRequestMock, $restTaxAppValidationAttributesTransfer);

        // Assert
        $this->assertCount(1, $restResponse->getErrors());
        $this->assertSame($expectedMessage, $restResponse->getErrors()[0]->getDetail());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $restResponse->getStatus());
    }

    /**
     * @return array<string, array<mixed>>
     */
    public function glossaryMessageDataProvider(): array
    {
        return [
            'with English locale and valid translation' => [
                'en_US',
                (new RestTaxAppValidationAttributesTransfer())
                    ->setTaxId('DE123456789')
                    ->setCountryCode('DE'),
                (new TaxAppValidationResponseTransfer())
                    ->setIsValid(false)
                    ->setMessage('Default error message')
                    ->setMessageKey(static::GLOSSARY_KEY_TAX_ID_INVALID),
                [
                    sprintf('%s.%s.en_US', static::GLOSSARY_SUFFIX_VERTEX, static::GLOSSARY_KEY_TAX_ID_INVALID) => 'The tax ID is invalid.',
                ],
                'The tax ID is invalid.',
            ],
            'with German locale and valid translation' => [
                'de_DE',
                (new RestTaxAppValidationAttributesTransfer())
                    ->setTaxId('DE123456789')
                    ->setCountryCode('DE'),
                (new TaxAppValidationResponseTransfer())
                    ->setIsValid(false)
                    ->setMessage('Default error message')
                    ->setMessageKey(static::GLOSSARY_KEY_TAX_ID_INVALID),
                [
                    sprintf('%s.%s.de_DE', static::GLOSSARY_SUFFIX_VERTEX, static::GLOSSARY_KEY_TAX_ID_INVALID) => 'Die Steuer-ID ist ungültig.',
                ],
                'Die Steuer-ID ist ungültig.',
            ],
            'with locale but no translation should use default message' => [
                'en_US',
                (new RestTaxAppValidationAttributesTransfer())
                    ->setTaxId('DE123456789')
                    ->setCountryCode('DE'),
                (new TaxAppValidationResponseTransfer())
                    ->setIsValid(false)
                    ->setMessage('Default error message')
                    ->setMessageKey(static::GLOSSARY_KEY_TAX_ID_INVALID),
                [
                    // No translation available for this key
                ],
                'Default error message',
            ],
            'with locale but no code should use default message' => [
                'en_US',
                (new RestTaxAppValidationAttributesTransfer())
                    ->setTaxId('DE123456789')
                    ->setCountryCode('DE'),
                (new TaxAppValidationResponseTransfer())
                    ->setIsValid(false)
                    ->setMessage('Default error message')
                    ->setMessageKey(null),
                [],
                'Default error message',
            ],
            'with different glossary key' => [
                'en_US',
                (new RestTaxAppValidationAttributesTransfer())
                    ->setTaxId('DE123456789')
                    ->setCountryCode('DE'),
                (new TaxAppValidationResponseTransfer())
                    ->setIsValid(false)
                    ->setMessage('Format is invalid')
                    ->setMessageKey(static::GLOSSARY_KEY_TAX_ID_FORMAT_INVALID),
                [
                    sprintf('%s.%s.en_US', static::GLOSSARY_SUFFIX_VERTEX, static::GLOSSARY_KEY_TAX_ID_FORMAT_INVALID) => 'The tax ID format is invalid.',
                ],
                'The tax ID format is invalid.',
            ],
        ];
    }
}
