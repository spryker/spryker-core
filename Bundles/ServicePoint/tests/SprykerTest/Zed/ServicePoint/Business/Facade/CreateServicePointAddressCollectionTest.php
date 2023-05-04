<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\ServicePoint\ServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePoint
 * @group Business
 * @group Facade
 * @group CreateServicePointAddressCollectionTest
 * Add your own group annotations below this line
 */
class CreateServicePointAddressCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ServicePoint\ServicePointBusinessTester
     */
    protected ServicePointBusinessTester $tester;

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\CountryAndRegionExistenceByIso2CodeServicePointAddressValidatorRule::GLOSSARY_KEY_VALIDATION_COUNTRY_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_COUNTRY_ENTITY_NOT_FOUND = 'service_point.validation.country_entity_not_found';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\CountryAndRegionExistenceByIso2CodeServicePointAddressValidatorRule::GLOSSARY_KEY_VALIDATION_REGION_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_REGION_ENTITY_NOT_FOUND = 'service_point.validation.region_entity_not_found';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\AddressLengthServicePointAddressValidatorRule::GLOSSARY_KEY_VALIDATION_ADDRESS1_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ADDRESS1_WRONG_LENGTH = 'service_point.validation.service_point_address_address1_wrong_length';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\AddressLengthServicePointAddressValidatorRule::GLOSSARY_KEY_VALIDATION_ADDRESS2_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ADDRESS2_WRONG_LENGTH = 'service_point.validation.service_point_address_address2_wrong_length';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\AddressLengthServicePointAddressValidatorRule::GLOSSARY_KEY_VALIDATION_ADDRESS3_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ADDRESS3_WRONG_LENGTH = 'service_point.validation.service_point_address_address3_wrong_length';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\CityLengthServicePointAddressValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_CITY_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_CITY_WRONG_LENGTH = 'service_point.validation.service_point_address_city_wrong_length';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ZipCodeLengthServicePointAddressValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ZIP_CODE_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ZIP_CODE_WRONG_LENGTH = 'service_point.validation.service_point_address_zip_code_wrong_length';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointExistenceByUuidServicePointAddressValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_SERVICE_POINT_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_SERVICE_POINT_ENTITY_NOT_FOUND = 'service_point.validation.service_point_entity_not_found';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointUuidUniquenessServicePointAddressValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_SERVICE_POINT_UUID_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_SERVICE_POINT_UUID_IS_NOT_UNIQUE = 'service_point.validation.service_point_uuid_is_not_unique';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointHasSingleServicePointAddressValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ALREADY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ALREADY_EXISTS = 'service_point.validation.service_point_address_already_exists';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureServicePointTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testCreatesServicePoint(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress((new ServicePointAddressTransfer())->fromArray($servicePointAddressTransfer->toArray(), true))
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $servicePointAddressCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->getServicePointAddressQuery()->count());
        /**
         * @var \Generated\Shared\Transfer\ServicePointAddressTransfer $persistedServicePointAddressTransfer
         */
        $persistedServicePointAddressTransfer = $servicePointAddressCollectionResponseTransfer->getServicePointAddresses()->getIterator()->current();
        $this->assertSame($servicePointAddressTransfer->getAddress1OrFail(), $persistedServicePointAddressTransfer->getAddress1OrFail());
        $this->assertSame($servicePointAddressTransfer->getAddress2OrFail(), $persistedServicePointAddressTransfer->getAddress2OrFail());
        $this->assertSame($servicePointAddressTransfer->getAddress3OrFail(), $persistedServicePointAddressTransfer->getAddress3OrFail());
        $this->assertSame($servicePointAddressTransfer->getZipCodeOrFail(), $persistedServicePointAddressTransfer->getZipCodeOrFail());
        $this->assertSame($servicePointAddressTransfer->getCityOrFail(), $persistedServicePointAddressTransfer->getCityOrFail());
        $this->assertNotNull($persistedServicePointAddressTransfer->getIdServicePointAddressOrFail());
        $this->assertSame(
            $servicePointAddressTransfer->getRegionOrFail()->getUuidOrFail(),
            $persistedServicePointAddressTransfer->getRegionOrFail()->getUuidOrFail(),
        );
        $this->assertSame(
            $servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail(),
            $persistedServicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testCreatesServicePointWithoutRegion(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer->setRegion(null);

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress((new ServicePointAddressTransfer())->fromArray($servicePointAddressTransfer->toArray(), true))
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $servicePointAddressCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->getServicePointAddressQuery()->count());
        /**
         * @var \Generated\Shared\Transfer\ServicePointAddressTransfer $persistedServicePointAddressTransfer
         */
        $persistedServicePointAddressTransfer = $servicePointAddressCollectionResponseTransfer->getServicePointAddresses()->getIterator()->current();
        $this->assertSame($servicePointAddressTransfer->getAddress1OrFail(), $persistedServicePointAddressTransfer->getAddress1OrFail());
        $this->assertSame($servicePointAddressTransfer->getAddress2OrFail(), $persistedServicePointAddressTransfer->getAddress2OrFail());
        $this->assertSame($servicePointAddressTransfer->getAddress3OrFail(), $persistedServicePointAddressTransfer->getAddress3OrFail());
        $this->assertSame($servicePointAddressTransfer->getZipCodeOrFail(), $persistedServicePointAddressTransfer->getZipCodeOrFail());
        $this->assertSame($servicePointAddressTransfer->getCityOrFail(), $persistedServicePointAddressTransfer->getCityOrFail());
        $this->assertNotNull($persistedServicePointAddressTransfer->getIdServicePointAddressOrFail());
        $this->assertNull($servicePointAddressTransfer->getRegion());
        $this->assertSame(
            $servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail(),
            $persistedServicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testValidatesCountryExistence(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer->getCountry()->setIso2Code('not-existing-iso2-code');

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointAddressCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_COUNTRY_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointAddressQuery()->count());
    }

    /**
     * @return void
     */
    public function testValidatesRegionExistence(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer->getRegion()->setUuid('not-existing-uuid');

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointAddressCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_REGION_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointAddressQuery()->count());
    }

    /**
     * @return void
     */
    public function testValidatesServicePointExistence(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer->getServicePoint()->setUuid('not-existing-uuid');

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointAddressCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_SERVICE_POINT_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointAddressQuery()->count());
    }

    /**
     * @return void
     */
    public function testValidatesServicePointUniqueness(): void
    {
        // Arrange
        $servicePointAddressTransfer1 = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer2 = $this->tester->createServicePointAddressTransferWithRelations([
            ServicePointAddressTransfer::SERVICE_POINT => [
                ServicePointTransfer::UUID => $servicePointAddressTransfer1->getServicePoint()->getUuid(),
            ],
        ]);

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer1)
            ->addServicePointAddress($servicePointAddressTransfer2)
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointAddressCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_SERVICE_POINT_UUID_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointAddressQuery()->count());
    }

    /**
     * @return void
     */
    public function testValidatesServicePointHasSingleAddress(): void
    {
        // Arrange
        $servicePointAddressTransfer1 = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer1 = $this->tester->haveServicePointAddress($servicePointAddressTransfer1->toArray());

        $servicePointAddressTransfer2 = $this->tester->createServicePointAddressTransferWithRelations([
            ServicePointAddressTransfer::SERVICE_POINT => [
                ServicePointTransfer::UUID => $servicePointAddressTransfer1->getServicePoint()->getUuid(),
            ],
        ]);

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer2)
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointAddressCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ALREADY_EXISTS, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getServicePointAddressQuery()->count());
    }

    /**
     * @dataProvider getRequiredAttributesValidation
     *
     * @param array $data
     *
     * @return void
     */
    public function testThrowsExceptionWhenRequiredAttributeNotSet(array $data): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer->fromArray($data, true);

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);
    }

    /**
     * @dataProvider getAttributesLengthValidation
     *
     * @param array $data
     * @param string $errorMessage
     *
     * @return void
     */
    public function testValidatesAttributeLength(array $data, string $errorMessage): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer->fromArray($data, true);

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointAddressCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame($errorMessage, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointAddressQuery()->count());
    }

    /**
     * @return array<list<mixed>>
     */
    protected function getRequiredAttributesValidation(): array
    {
        return [
            [
                [
                    ServicePointAddressTransfer::ADDRESS1 => null,
                ],
            ],
            [
                [
                    ServicePointAddressTransfer::ADDRESS2 => null,
                ],
            ],
            [
                [
                    ServicePointAddressTransfer::CITY => null,
                ],
            ],
            [
                [
                    ServicePointAddressTransfer::ZIP_CODE => null,
                ],
            ],
            [
                [
                    ServicePointAddressTransfer::COUNTRY => [
                        CountryTransfer::ISO2_CODE => null,
                    ],
                ],
            ],
            [
                [
                    ServicePointAddressTransfer::SERVICE_POINT => [
                        ServicePointTransfer::UUID => null,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<list<mixed>>
     */
    protected function getAttributesLengthValidation(): array
    {
        $longString = str_repeat('a', 256);

        return [
            [
                [
                    ServicePointAddressTransfer::ADDRESS1 => '',
                ],
                static::GLOSSARY_KEY_VALIDATION_ADDRESS1_WRONG_LENGTH,
            ],
            [
                [
                    ServicePointAddressTransfer::ADDRESS1 => $longString,
                ],
                static::GLOSSARY_KEY_VALIDATION_ADDRESS1_WRONG_LENGTH,
            ],
            [
                [
                    ServicePointAddressTransfer::ADDRESS2 => '',
                ],
                static::GLOSSARY_KEY_VALIDATION_ADDRESS2_WRONG_LENGTH,
            ],
            [
                [
                    ServicePointAddressTransfer::ADDRESS2 => $longString,
                ],
                static::GLOSSARY_KEY_VALIDATION_ADDRESS2_WRONG_LENGTH,
            ],
            [
                [
                    ServicePointAddressTransfer::ADDRESS3 => '',
                ],
                static::GLOSSARY_KEY_VALIDATION_ADDRESS3_WRONG_LENGTH,
            ],
            [
                [
                    ServicePointAddressTransfer::ADDRESS3 => $longString,
                ],
                static::GLOSSARY_KEY_VALIDATION_ADDRESS3_WRONG_LENGTH,
            ],
            [
                [
                    ServicePointAddressTransfer::CITY => '',
                ],
                static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_CITY_WRONG_LENGTH,
            ],
            [
                [
                    ServicePointAddressTransfer::CITY => $longString,
                ],
                static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_CITY_WRONG_LENGTH,
            ],
            [
                [
                    ServicePointAddressTransfer::ZIP_CODE => '',
                ],
                static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ZIP_CODE_WRONG_LENGTH,
            ],
            [
                [
                    ServicePointAddressTransfer::ZIP_CODE => str_repeat('a', 17),
                ],
                static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ZIP_CODE_WRONG_LENGTH,
            ],
        ];
    }
}
