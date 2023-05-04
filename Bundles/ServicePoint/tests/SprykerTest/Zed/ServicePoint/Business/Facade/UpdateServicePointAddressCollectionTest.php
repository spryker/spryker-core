<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionTransfer;
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
 * @group UpdateServicePointAddressCollectionTest
 * Add your own group annotations below this line
 */
class UpdateServicePointAddressCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ServicePoint\ServicePointBusinessTester
     */
    protected ServicePointBusinessTester $tester;

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

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
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ExistenceByUuidServicePointAddressValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ENTITY_NOT_FOUND = 'service_point.validation.service_point_address_entity_not_found';

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
    public function testUpdatesServicePoint(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $newValue = '123456';
        $newCountryTransfer = $this->tester->haveCountryTransfer([
            CountryTransfer::ISO2_CODE => '02',
            CountryTransfer::ISO3_CODE => '002',
        ]);
        $newRegionTransfer = $this->tester->haveRegion([
            RegionTransfer::FK_COUNTRY => $newCountryTransfer->getIdCountry(),
        ]);

        $servicePointAddressTransfer->setAddress1($newValue)
            ->setAddress2($newValue)
            ->setAddress3($newValue)
            ->setCity($newValue)
            ->setZipCode($newValue)
            ->setRegion($newRegionTransfer)
            ->setCountry($newCountryTransfer);

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress(
                (new ServicePointAddressTransfer())->fromArray($servicePointAddressTransfer->toArray(), true),
            )
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $servicePointAddressCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->getServicePointAddressQuery()->count());
        /**
         * @var \Generated\Shared\Transfer\ServicePointAddressTransfer $persistedServicePointAddressTransfer
         */
        $persistedServicePointAddressTransfer = $servicePointAddressCollectionResponseTransfer->getServicePointAddresses()->getIterator()->current();
        $this->assertEquals($servicePointAddressTransfer->getUuidOrFail(), $persistedServicePointAddressTransfer->getUuidOrFail());
        $this->assertEquals($newValue, $persistedServicePointAddressTransfer->getAddress1OrFail());
        $this->assertEquals($newValue, $persistedServicePointAddressTransfer->getAddress2OrFail());
        $this->assertEquals($newValue, $persistedServicePointAddressTransfer->getAddress3OrFail());
        $this->assertEquals($newValue, $persistedServicePointAddressTransfer->getCityOrFail());
        $this->assertEquals($newValue, $persistedServicePointAddressTransfer->getZipCodeOrFail());
        $this->assertSame($servicePointAddressTransfer->getIdServicePointAddressOrFail(), $persistedServicePointAddressTransfer->getIdServicePointAddressOrFail());
        $this->assertSame($newRegionTransfer->getUuidOrFail(), $persistedServicePointAddressTransfer->getRegionOrFail()->getUuidOrFail());
        $this->assertSame($newCountryTransfer->getIso2CodeOrFail(), $persistedServicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail());
    }

    /**
     * @return void
     */
    public function testUpdatesServicePointOptionalAttributesToNull(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointAddressTransfer
            ->setAddress3(null)
            ->setRegion(null);

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress(
                (new ServicePointAddressTransfer())->fromArray($servicePointAddressTransfer->toArray(), true),
            )
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $servicePointAddressCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->getServicePointAddressQuery()->count());
        /**
         * @var \Generated\Shared\Transfer\ServicePointAddressTransfer $persistedServicePointAddressTransfer
         */
        $persistedServicePointAddressTransfer = $servicePointAddressCollectionResponseTransfer->getServicePointAddresses()->getIterator()->current();
        $this->assertEquals($servicePointAddressTransfer->getUuidOrFail(), $persistedServicePointAddressTransfer->getUuidOrFail());
        $this->assertEquals($servicePointAddressTransfer->getAddress1OrFail(), $persistedServicePointAddressTransfer->getAddress1OrFail());
        $this->assertEquals($servicePointAddressTransfer->getAddress2OrFail(), $persistedServicePointAddressTransfer->getAddress2OrFail());
        $this->assertNull($persistedServicePointAddressTransfer->getAddress3());
        $this->assertEquals($servicePointAddressTransfer->getCityOrFail(), $persistedServicePointAddressTransfer->getCityOrFail());
        $this->assertEquals($servicePointAddressTransfer->getZipCodeOrFail(), $persistedServicePointAddressTransfer->getZipCodeOrFail());
        $this->assertSame($servicePointAddressTransfer->getIdServicePointAddressOrFail(), $persistedServicePointAddressTransfer->getIdServicePointAddressOrFail());
        $this->assertNull($persistedServicePointAddressTransfer->getRegion());
        $this->assertSame($servicePointAddressTransfer->getCountry()->getIso2CodeOrFail(), $persistedServicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail());
    }

    /**
     * @return void
     */
    public function testValidatesCountryExistence(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointAddressTransfer->getCountry()->setIso2Code('not-existing-iso2-code');

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointAddressCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_COUNTRY_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testValidatesRegionExistence(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointAddressTransfer->getRegion()->setUuid('not-existing-uuid');

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointAddressCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_REGION_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testValidatesServicePointExistence(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointAddressTransfer->getServicePoint()->setUuid('not-existing-uuid');

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointAddressCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_SERVICE_POINT_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testValidatesServicePointAddressExistence(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointAddressTransfer->setUuid('not-existing-uuid');

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointAddressCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testValidatesServicePointUniqueness(): void
    {
        // Arrange
        $servicePointAddressTransfer1 = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer1 = $this->tester->haveServicePointAddress($servicePointAddressTransfer1->toArray());

        $servicePointAddressTransfer2 = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer2 = $this->tester->haveServicePointAddress($servicePointAddressTransfer2->toArray());

        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE);
        $servicePointTransfer = $this->tester->haveServicePoint($servicePointTransfer->toArray());

        $servicePointAddressTransfer1->getServicePoint()->setUuid($servicePointTransfer->getUuid());
        $servicePointAddressTransfer2->getServicePoint()->setUuid($servicePointTransfer->getUuid());

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer1)
            ->addServicePointAddress($servicePointAddressTransfer2)
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointAddressCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_SERVICE_POINT_UUID_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testValidatesServicePointHasSingleAddress(): void
    {
        // Arrange
        $servicePointAddressTransfer1 = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer1 = $this->tester->haveServicePointAddress($servicePointAddressTransfer1->toArray());

        $servicePointAddressTransfer2 = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer2 = $this->tester->haveServicePointAddress($servicePointAddressTransfer2->toArray());

        $servicePointAddressTransfer2->getServicePoint()->setUuid($servicePointAddressTransfer1->getServicePoint()->getUuid());

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
        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());
        $servicePointAddressTransfer->fromArray($data, true);

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);
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
        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());
        $servicePointAddressTransfer->fromArray($data, true);

        $servicePointAddressCollectionRequestTransfer = (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointAddressCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointAddressCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame($errorMessage, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return array<list<mixed>>
     */
    protected function getRequiredAttributesValidation(): array
    {
        return [
            [
                [
                    ServicePointAddressTransfer::UUID => null,
                ],
            ],
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
                    ServicePointAddressTransfer::ADDRESS3 => $longString,
                ],
                static::GLOSSARY_KEY_VALIDATION_ADDRESS3_WRONG_LENGTH,
            ],
            [
                [
                    ServicePointAddressTransfer::ADDRESS3 => '',
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
