<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\ProductOfferServicePoint\Business\Exception\ProductOfferValidationException;
use SprykerTest\Zed\ProductOfferServicePoint\ProductOfferServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferServicePoint
 * @group Business
 * @group Facade
 * @group SaveProductOfferServicesTest
 * Add your own group annotations below this line
 */
class SaveProductOfferServicesTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_INVALID = 'not-existing-product-offer-reference';

    /**
     * @var string
     */
    protected const SERVICE_UUID_INVALID = 'not-existing-service-uuid';

    /**
     * @uses \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\HasSingleServicePointProductOfferValidatorRule::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_HAS_MULTIPLE_SERVICE_POINTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_HAS_MULTIPLE_SERVICE_POINTS = 'product_offer_service_point.validation.product_offer_has_multiple_service_points';

    /**
     * @uses \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ReferenceExistsProductOfferValidatorRule::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_REFERENCE_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_REFERENCE_NOT_FOUND = 'product_offer_service_point.validation.product_offer_reference_not_found';

    /**
     * @uses \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ServiceExistsProductOfferValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_UUID_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_UUID_NOT_FOUND = 'product_offer_service_point.validation.service_uuid_not_found';

    /**
     * @uses \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ServiceUniquenessProductOfferValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_NOT_UNIQUE = 'product_offer_service_point.validation.service_not_unique';

    /**
     * @uses \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\UniquenessProductOfferValidatorRule::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_NOT_UNIQUE = 'product_offer_service_point.validation.product_offer_not_unique';

    /**
     * @var \SprykerTest\Zed\ProductOfferServicePoint\ProductOfferServicePointBusinessTester
     */
    protected ProductOfferServicePointBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductOfferServiceTableAndRelationsAreEmpty();
    }

    /**
     * @return void
     */
    public function testShouldSaveProductOfferServices(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferTransfer->addService($this->tester->haveService());
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setThrowExceptionOnValidation(true)
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasServicesPersisted($productOfferTransfer);
    }

    /**
     * @return void
     */
    public function testShouldDeleteOldAndCreateNewProductOfferServices(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $this->tester->haveService()->getIdServiceOrFail(),
        ]);
        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $this->tester->haveService()->getIdServiceOrFail(),
        ]);

        $persistedServiceTransfer = $this->tester->haveService();

        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setThrowExceptionOnValidation(true)
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasServicesPersisted($productOfferTransfer);
    }

    /**
     * @return void
     */
    public function testShouldDeleteAllProductOfferServices(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $this->tester->haveService()->getIdServiceOrFail(),
        ]);
        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $this->tester->haveService()->getIdServiceOrFail(),
        ]);

        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setThrowExceptionOnValidation(true)
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasServicesPersisted($productOfferTransfer);
    }

    /**
     * @return void
     */
    public function testShouldWorkWithoutProductOfferServices(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addProductOffer($productOfferTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasServicesPersisted($productOfferTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceIsDuplicatedInProductOffer(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setThrowExceptionOnValidation(true)
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(ProductOfferValidationException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenServiceIsDuplicatedInProductOffer(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addProductOffer($productOfferTransfer);

        // Act
        $productOfferServiceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productOfferServiceCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $productOfferServiceCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getNumberOfPersistedProductOfferServices($productOfferTransfer));
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenProductOfferIsDuplicated(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setThrowExceptionOnValidation(true)
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer)
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(ProductOfferValidationException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenProductOfferIsDuplicated(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer)
            ->addProductOffer($productOfferTransfer);

        // Act
        $productOfferServiceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productOfferServiceCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $productOfferServiceCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getNumberOfPersistedProductOfferServices($productOfferTransfer));
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWithNotSingleServicePoint(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $persistedServiceTransfer2 = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferTransfer->addService($persistedServiceTransfer2);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setThrowExceptionOnValidation(true)
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(ProductOfferValidationException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWithNotSingleServicePoint(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $persistedServiceTransfer2 = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferTransfer->addService($persistedServiceTransfer2);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addProductOffer($productOfferTransfer);

        // Act
        $productOfferServiceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productOfferServiceCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $productOfferServiceCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_HAS_MULTIPLE_SERVICE_POINTS, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getNumberOfPersistedProductOfferServices($productOfferTransfer));
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenProductOfferReferenceIsInvalid(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $productOfferTransfer->addService($persistedServiceTransfer);

        $productOfferTransfer->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_INVALID);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setThrowExceptionOnValidation(true)
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(ProductOfferValidationException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenProductOfferReferenceIsInvalid(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $productOfferTransfer->addService($persistedServiceTransfer);

        $productOfferTransfer->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_INVALID);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addProductOffer($productOfferTransfer);

        // Act
        $productOfferServiceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productOfferServiceCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $productOfferServiceCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_REFERENCE_NOT_FOUND, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getNumberOfPersistedProductOfferServices($productOfferTransfer));
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceUuidIsInvalid(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $persistedServiceTransfer->setUuid(static::SERVICE_UUID_INVALID);
        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setThrowExceptionOnValidation(true)
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(ProductOfferValidationException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenServiceUuidIsInvalid(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $persistedServiceTransfer->setUuid(static::SERVICE_UUID_INVALID);
        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addProductOffer($productOfferTransfer);

        // Act
        $productOfferServiceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productOfferServiceCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $productOfferServiceCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_UUID_NOT_FOUND, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getNumberOfPersistedProductOfferServices($productOfferTransfer));
    }

    /**
     * @group test
     *
     * @return void
     */
    public function testShouldExpandProductOfferWithIdProductOfferWhenIdProductOfferIsMissing(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferTransfer->addService($this->tester->haveService());

        $expectedIdProductOffer = $productOfferTransfer->getIdProductOfferOrFail();
        $productOfferTransfer->setIdProductOffer(null);

        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setThrowExceptionOnValidation(true)
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasServicesPersisted($productOfferTransfer);
        $this->assertSame($expectedIdProductOffer, $productOfferTransfer->getIdProductOfferOrFail());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenProductOfferReferenceIsMissing(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $productOfferTransfer->addService($persistedServiceTransfer);

        $productOfferTransfer->setProductOfferReference(null);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setThrowExceptionOnValidation(true)
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWithNotSingleServicePointWhenServiceWithWrongServicePointUuidsPassed(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $persistedServiceTransfer2 = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $persistedServiceTransfer2->setServicePoint($persistedServiceTransfer->getServicePointOrFail());

        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferTransfer->addService($persistedServiceTransfer2);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setThrowExceptionOnValidation(true)
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(ProductOfferValidationException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWithNotSingleServicePointWhenServiceWithWrongServicePointUuidsPassed(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $persistedServiceTransfer2 = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $persistedServiceTransfer2->setServicePoint($persistedServiceTransfer->getServicePointOrFail());

        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferTransfer->addService($persistedServiceTransfer2);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addProductOffer($productOfferTransfer);

        // Act
        $productOfferServiceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productOfferServiceCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $productOfferServiceCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_HAS_MULTIPLE_SERVICE_POINTS, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getNumberOfPersistedProductOfferServices($productOfferTransfer));
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceUuidIsMissing(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $persistedServiceTransfer->setUuid();

        $productOfferTransfer->addService($persistedServiceTransfer);
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->setThrowExceptionOnValidation(true)
            ->setIsTransactional(false)
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return void
     */
    protected function assertProductOfferHasServicesPersisted(ProductOfferTransfer $productOfferTransfer): void
    {
        $this->assertSame($productOfferTransfer->getServices()->count(), $this->tester->getNumberOfPersistedProductOfferServices($productOfferTransfer));

        foreach ($productOfferTransfer->getServices() as $serviceTransfer) {
            $this->assertTrue($this->tester->hasProductOfferService($productOfferTransfer, $serviceTransfer));
        }
    }
}
