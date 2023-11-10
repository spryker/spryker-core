<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsBackendApi\Controller\ServicePointAddressesResourceController;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Spryker\Glue\ServicePointsBackendApi\Controller\ServicePointAddressesResourceController;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Client\ServicePointsBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiDependencyProvider;
use SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ServicePointsBackendApi
 * @group Controller
 * @group ServicePointAddressesResourceController
 * @group GetCollectionTest
 * Add your own group annotations below this line
 */
class GetCollectionTest extends Unit
{
    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::RESPONSE_CODE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const RESPONSE_CODE_ENTITY_NOT_FOUND = '5403';

    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND = 'service_point.validation.service_point_entity_not_found';

    /**
     * @var string
     */
    protected const ISO2_CODE_DE = 'DE';

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var \SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester
     */
    protected ServicePointsBackendApiTester $tester;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Controller\ServicePointAddressesResourceController
     */
    protected ServicePointAddressesResourceController $servicePointAddressesResourceController;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->servicePointAddressesResourceController = new ServicePointAddressesResourceController();
    }

    /**
     * @return void
     */
    public function testShouldReturnGlueResponseTransferWithServicePointAddressCollectionTransfer(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->haveServicePoint();

        $countryTransfer = $this->tester->haveCountry([
            CountryTransfer::ISO2_CODE => static::ISO2_CODE_DE,
        ]);

        $servicePointAddressTransfer = $this->tester->haveServicePointAddress([
            ServicePointAddressTransfer::SERVICE_POINT => $servicePointTransfer,
            ServicePointAddressTransfer::COUNTRY => $countryTransfer,
        ]);

        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setLocale(static::LOCALE_DE)
            ->addParentResource(2, (new GlueResourceTransfer())->setId($servicePointTransfer->getUuidOrFail()));

        // Act
        $glueResponseTransfer = $this->servicePointAddressesResourceController->getCollectionAction($glueRequestTransfer);

        // Assert
        $this->assertCount(1, $glueResponseTransfer->getResources());
        $this->assertCount(0, $glueResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueResponseTransfer->getResources()->getIterator()->current();

        /** @var \Generated\Shared\Transfer\ServicePointAddressesBackendApiAttributesTransfer $servicePointAddressesBackendApiAttributesTransfer */
        $servicePointAddressesBackendApiAttributesTransfer = $glueResourceTransfer->getAttributesOrFail();

        $this->assertSame($servicePointAddressTransfer->getUuidOrFail(), $servicePointAddressesBackendApiAttributesTransfer->getUuidOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnGlueResponseTransferWithErrorWhileServicePointNotFound(): void
    {
        // Arrange
        $this->tester->setDependency(
            ServicePointsBackendApiDependencyProvider::CLIENT_GLOSSARY_STORAGE,
            $this->createGlossaryStorageClientMock(),
        );

        $glueResourceTransfer = (new GlueResourceTransfer())->setId('invalidServicePointUuid');
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setLocale(static::LOCALE_DE)
            ->addParentResource(2, $glueResourceTransfer);

        // Act
        $glueResponseTransfer = $this->servicePointAddressesResourceController->getCollectionAction($glueRequestTransfer);

        // Assert
        $this->assertCount(1, $glueResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\GlueErrorTransfer $glueErrorTransfer */
        $glueErrorTransfer = $glueResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND, $glueErrorTransfer->getMessageOrFail());
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueErrorTransfer->getStatusOrFail());
        $this->assertSame(static::RESPONSE_CODE_ENTITY_NOT_FOUND, $glueErrorTransfer->getCodeOrFail());
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Dependency\Client\ServicePointsBackendApiToGlossaryStorageClientInterface
     */
    protected function createGlossaryStorageClientMock(): ServicePointsBackendApiToGlossaryStorageClientInterface
    {
        $glossaryStorageClientMock = $this->getMockBuilder(ServicePointsBackendApiToGlossaryStorageClientInterface::class)->getMock();

        $glossaryStorageClientMock->method('translateBulk')
            ->will(
                $this->returnCallback(function (array $keys, string $localeName) {
                    $translations = [];

                    foreach ($keys as $key) {
                        $translations[$key] = $key;
                    }

                    return $translations;
                }),
            );

        return $glossaryStorageClientMock;
    }
}
