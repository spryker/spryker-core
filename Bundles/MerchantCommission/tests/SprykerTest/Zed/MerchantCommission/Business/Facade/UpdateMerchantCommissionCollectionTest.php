<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\MerchantCommissionAmountBuilder;
use Generated\Shared\DataBuilder\MerchantCommissionGroupBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\MerchantCommission\MerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommission
 * @group Business
 * @group Facade
 * @group UpdateMerchantCommissionCollectionTest
 * Add your own group annotations below this line
 */
class UpdateMerchantCommissionCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var string
     */
    protected const CURRENCY_CODE_EUR = 'EUR';

    /**
     * @var string
     */
    protected const CURRENCY_CODE_USD = 'USD';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\MerchantCommissionGroupExistsMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_GROUP_DOES_NOT_EXIST
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_DOES_NOT_EXIST = 'merchant_commission.validation.merchant_commission_does_not_exist';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\CurrencyExistsMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_CURRENCY_DOES_NOT_EXIST
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CURRENCY_DOES_NOT_EXIST = 'merchant_commission.validation.currency_does_not_exist';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\DescriptionLengthMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_DESCRIPTION_INVALID_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_DESCRIPTION_INVALID_LENGTH = 'merchant_commission.validation.merchant_commission_description_invalid_length';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\KeyExistsMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_EXISTS = 'merchant_commission.validation.merchant_commission_key_exists';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\KeyLengthMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_INVALID_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_INVALID_LENGTH = 'merchant_commission.validation.merchant_commission_key_invalid_length';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\KeyUniqueMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_IS_NOT_UNIQUE = 'merchant_commission.validation.merchant_commission_key_is_not_unique';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\MerchantCommissionGroupExistsMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_GROUP_DOES_NOT_EXIST
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_GROUP_DOES_NOT_EXIST = 'merchant_commission.validation.merchant_commission_group_does_not_exist';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\MerchantExistsMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_DOES_NOT_EXIST
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_DOES_NOT_EXIST = 'merchant_commission.validation.merchant_does_not_exist';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\NameLengthMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_NAME_INVALID_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_NAME_INVALID_LENGTH = 'merchant_commission.validation.merchant_commission_name_invalid_length';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\PriorityRangeMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_PRIORITY_NOT_IN_RANGE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_PRIORITY_NOT_IN_RANGE = 'merchant_commission.validation.merchant_commission_priority_not_in_range';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\StoreExistsMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST = 'merchant_commission.validation.store_does_not_exist';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\ValidFromDateTimeMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALID_FROM_INVALID_DATETIME
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALID_FROM_INVALID_DATETIME = 'merchant_commission.validation.merchant_commission_valid_from_invalid_datetime';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\ValidToDateTimeMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALID_TO_INVALID_DATETIME
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALID_TO_INVALID_DATETIME = 'merchant_commission.validation.merchant_commission_valid_to_invalid_datetime';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\ValidityPeriodMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALIDITY_PERIOD_INVALID
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALIDITY_PERIOD_INVALID = 'merchant_commission.validation.merchant_commission_validity_period_invalid';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\CalculatorTypePluginExistsMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_CALCULATOR_TYPE_PLUGIN_MISSING
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CALCULATOR_TYPE_PLUGIN_MISSING = 'merchant_commission.validation.calculator_type_plugin_is_missing';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\AbstractQueryStringMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_INVALID_QUERY_STRING
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_INVALID_QUERY_STRING = 'merchant_commission.validation.invalid_query_string';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\AbstractQueryStringMerchantCommissionValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_INVALID_COMPARE_OPERATOR
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_INVALID_COMPARE_OPERATOR = 'merchant_commission.validation.invalid_compare_operator';

    /**
     * @var \SprykerTest\Zed\MerchantCommission\MerchantCommissionBusinessTester
     */
    protected MerchantCommissionBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->addTestCalculatorPluginToDependencies();
    }

    /**
     * @return void
     */
    public function testUpdatesMerchantCommission(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionName = 'Updated Name';
        $merchantCommissionTransfer->setName($merchantCommissionName);

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $merchantCommissionCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $this->assertSame(
            $merchantCommissionName,
            $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current()->getName(),
        );

        $merchantCommissionEntity = $this->tester->getMerchantCommissionEntity(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
        );
        $this->assertSame($merchantCommissionName, $merchantCommissionEntity->getName());
    }

    /**
     * @return void
     */
    public function testUpdatesMerchantCommissionStoreRelation(): void
    {
        $storeDeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeAtTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeDeTransfer),
        ]);

        $merchantCommissionTransfer->getStoreRelationOrFail()->setStores(new ArrayObject([$storeAtTransfer]));

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $merchantCommissionCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());

        $this->assertFalse($this->tester->merchantCommissionStoreRelationExists(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $storeDeTransfer->getIdStoreOrFail(),
        ));
        $this->assertTrue($this->tester->merchantCommissionStoreRelationExists(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $storeAtTransfer->getIdStoreOrFail(),
        ));
    }

    /**
     * @return void
     */
    public function testUpdatesMerchantCommissionMerchantRelation(): void
    {
        $merchant1Transfer = $this->tester->haveMerchant();
        $merchant2Transfer = $this->tester->haveMerchant();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANTS => [$merchant1Transfer->toArray()],
        ]);

        $merchantCommissionTransfer->setMerchants(new ArrayObject([$merchant2Transfer]));

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $merchantCommissionCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());

        $this->assertFalse($this->tester->merchantCommissionMerchantRelationExists(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchant1Transfer->getIdMerchantOrFail(),
        ));
        $this->assertTrue($this->tester->merchantCommissionMerchantRelationExists(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchant2Transfer->getIdMerchantOrFail(),
        ));
    }

    /**
     * @return void
     */
    public function testUpdatesMerchantCommissionAmounts(): void
    {
        $currencyEurTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR]);
        $currencyUsdTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => static::CURRENCY_CODE_USD]);
        $merchantCommissionAmount1Transfer = (new MerchantCommissionAmountBuilder([
            MerchantCommissionAmountTransfer::CURRENCY => $currencyEurTransfer,
            MerchantCommissionAmountTransfer::GROSS_AMOUNT => 200,
        ]))->build();
        $merchantCommissionAmount2Transfer = (new MerchantCommissionAmountBuilder([
            MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::CODE => $currencyUsdTransfer->getCodeOrFail()],
            MerchantCommissionAmountTransfer::GROSS_AMOUNT => 300,
        ]))->build();

        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_AMOUNTS => [
                $merchantCommissionAmount1Transfer->toArray(),
            ],
        ]);

        $merchantCommissionTransfer->getMerchantCommissionAmounts()->offsetGet(0)->setGrossAmount(100);
        $merchantCommissionTransfer->addMerchantCommissionAmount($merchantCommissionAmount2Transfer);

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $merchantCommissionCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());

        $merchantCommissionAmount1Entity = $this->tester->findMerchantCommissionAmountEntity(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $currencyEurTransfer->getIdCurrencyOrFail(),
        );
        $this->assertNotNull($merchantCommissionAmount1Entity);
        $this->assertSame(100, $merchantCommissionAmount1Entity->getGrossAmount());

        $merchantCommissionAmount2Entity = $this->tester->findMerchantCommissionAmountEntity(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $currencyUsdTransfer->getIdCurrencyOrFail(),
        );
        $this->assertNotNull($merchantCommissionAmount2Entity);
        $this->assertSame(300, $merchantCommissionAmount2Entity->getGrossAmount());
    }

    /**
     * @return void
     */
    public function testReturnsValidationErrorWhenMerchantCommissionWithProvidedKeyIsNotFound(): void
    {
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();

        $merchantCommissionTransfer->setKey('non-existing-key');
        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_DOES_NOT_EXIST,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenProvidedCurrencyDoesNotExist(): void
    {
        // Arrange
        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR]);
        $merchantCommissionAmountTransfer = (new MerchantCommissionAmountBuilder([
            MerchantCommissionAmountTransfer::CURRENCY => $currencyTransfer,
        ]))->build();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_AMOUNTS => [$merchantCommissionAmountTransfer->toArray()],
        ]);

        $merchantCommissionTransfer->getMerchantCommissionAmounts()
            ->offsetGet(0)
            ->getCurrencyOrFail()
            ->setCode('INVALID');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_CURRENCY_DOES_NOT_EXIST,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $this->assertNotNull($this->tester->findMerchantCommissionAmountEntity(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $currencyTransfer->getIdCurrencyOrFail(),
        ));
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenDescriptionExceedsLengthLimit(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->setDescription(str_repeat('a', 1025));

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_DESCRIPTION_INVALID_LENGTH,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenProvidedMerchantCommissionGroupDoesNotExist(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();

        $merchantCommissionGroupTransfer = (new MerchantCommissionGroupBuilder([
            MerchantCommissionGroupTransfer::UUID => 'non-existing-merchant-commission-group-uuid',
        ]))->build();
        $merchantCommissionTransfer->setMerchantCommissionGroup($merchantCommissionGroupTransfer);

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_GROUP_DOES_NOT_EXIST,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenProvidedMerchantDoesNotExist(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->addMerchant(
            (new MerchantTransfer())->setMerchantReference('non-existing-merchant-reference'),
        );

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_DOES_NOT_EXIST,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenNameExceedsLengthLimit(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->setName(str_repeat('a', 256));

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_NAME_INVALID_LENGTH,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenPriorityExceedsRangeLimit(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->setPriority(10000);

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_PRIORITY_NOT_IN_RANGE,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenProvidedStoreDoesNotExist(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->getStoreRelationOrFail()->addStores(
            (new StoreTransfer())->setName('XX'),
        );

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenValidFromIsNotValidDateTime(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->setValidFrom('not-a-datetime');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALID_FROM_INVALID_DATETIME,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenValidToIsNotValidDateTime(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->setValidTo('not-a-datetime');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALID_TO_INVALID_DATETIME,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenValidToDateIsEarlierThanValidFromDate(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->setValidFrom((new DateTime('+1 day'))->format('Y-m-d H:i:s'));
        $merchantCommissionTransfer->setValidTo((new DateTime('-1 day'))->format('Y-m-d H:i:s'));

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALIDITY_PERIOD_INVALID,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenProvidedCalculatorTypePluginIsMissing(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->setCalculatorTypePlugin('non-existing-calculator-type-plugin');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_CALCULATOR_TYPE_PLUGIN_MISSING,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenOrderConditionContainsIncorrectCompareOperator(): void
    {
        // Arrange
        $this->tester->addOrderDecisionRulePluginToDependencies();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->setOrderCondition('test-order-field >= "test"');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_INVALID_COMPARE_OPERATOR,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenOrderConditionContainsIncorrectQueryString(): void
    {
        // Arrange
        $this->tester->addOrderDecisionRulePluginToDependencies();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->setOrderCondition('test-order-field ? "test"');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_INVALID_QUERY_STRING,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenItemConditionContainsIncorrectCompareOperator(): void
    {
        // Arrange
        $this->tester->addOrderItemCollectorRulePluginToDependencies();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->setItemCondition('test-order-item-field >= "test"');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_INVALID_COMPARE_OPERATOR,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenItemConditionContainsIncorrectQueryString(): void
    {
        // Arrange
        $this->tester->addOrderItemCollectorRulePluginToDependencies();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();
        $merchantCommissionTransfer->setItemCondition('test-order-item-field = test');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_INVALID_QUERY_STRING,
        );
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
    }
}
