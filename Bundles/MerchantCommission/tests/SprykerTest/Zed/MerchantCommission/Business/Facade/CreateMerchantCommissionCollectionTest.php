<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\MerchantCommissionAmountBuilder;
use Generated\Shared\DataBuilder\MerchantCommissionBuilder;
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
 * @group CreateMerchantCommissionCollectionTest
 * Add your own group annotations below this line
 */
class CreateMerchantCommissionCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const CURRENCY_CODE_EUR = 'EUR';

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
     * @uses \Spryker\Zed\RuleEngine\Business\Validator\QueryStringValidator::GLOSSARY_KEY_INVALID_QUERY_STRING
     *
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_QUERY_STRING = 'rule_engine.validation.invalid_query_string';

    /**
     * @uses \Spryker\Zed\RuleEngine\Business\Validator\QueryStringValidator::GLOSSARY_KEY_INVALID_COMPARE_OPERATOR_VALUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_COMPARE_OPERATOR_VALUE = 'rule_engine.validation.invalid_compare_operator_value';

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
    public function testCreatesMerchantCommissionWithStoreRelation(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $merchantCommissionGroupTransfer = $this->tester->haveMerchantCommissionGroup();
        $merchantCommissionTransfer = (new MerchantCommissionBuilder([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => [
                MerchantCommissionGroupTransfer::UUID => $merchantCommissionGroupTransfer->getUuidOrFail(),
            ],
            MerchantCommissionTransfer::STORE_RELATION => [
                StoreRelationTransfer::STORES => [
                    [StoreTransfer::NAME => $storeTransfer->getNameOrFail()],
                ],
            ],
        ]))->build();

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $merchantCommissionCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());

        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNotNull($merchantCommissionTransfer->getUuid());
        $this->assertNotNull($merchantCommissionTransfer->getIdMerchantCommission());
        $this->assertTrue($this->tester->merchantCommissionStoreRelationExists(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $storeTransfer->getIdStoreOrFail(),
        ));
    }

    /**
     * @return void
     */
    public function testCreatesMerchantCommissionWithMerchantRelation(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->addMerchant($merchantTransfer);

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $merchantCommissionCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());

        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNotNull($merchantCommissionTransfer->getUuid());
        $this->assertNotNull($merchantCommissionTransfer->getIdMerchantCommission());
        $this->assertTrue($this->tester->merchantCommissionMerchantRelationExists(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantTransfer->getIdMerchantOrFail(),
        ));
    }

    /**
     * @return void
     */
    public function testCreatesMerchantCommissionWithMerchantCommissionAmount(): void
    {
        // Arrange
        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR]);
        $merchantCommissionAmountTransfer = (new MerchantCommissionAmountBuilder([
            MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::CODE => $currencyTransfer->getCodeOrFail()],
        ]))->build();

        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->addMerchantCommissionAmount($merchantCommissionAmountTransfer);

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $merchantCommissionCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());

        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNotNull($merchantCommissionTransfer->getUuid());
        $this->assertNotNull($merchantCommissionTransfer->getIdMerchantCommission());

        $merchantCommissionAmountTransfer = $merchantCommissionTransfer->getMerchantCommissionAmounts()->getIterator()->current();
        $this->assertNotNull($merchantCommissionAmountTransfer->getUuid());
        $this->assertNotNull($merchantCommissionAmountTransfer->getIdMerchantCommissionAmount());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenProvidedCurrencyDoesNotExist(): void
    {
        // Arrange
        $merchantCommissionAmountTransfer = (new MerchantCommissionAmountBuilder([
            MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::CODE => 'XXX'],
        ]))->build();
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->addMerchantCommissionAmount($merchantCommissionAmountTransfer);

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_CURRENCY_DOES_NOT_EXIST,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenDescriptionExceedsLengthLimit(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setDescription(str_repeat('a', 1025));

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_DESCRIPTION_INVALID_LENGTH,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenMerchantCommissionWithSameKeyAlreadyExistsInPersistence(): void
    {
        // Arrange
        $merchantCommissionKey = 'test-merchant-commission-key';
        $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::KEY => $merchantCommissionKey,
        ]);

        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setKey($merchantCommissionKey);

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_EXISTS,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenKeyExceedsLengthLimit(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setKey(str_repeat('a', 256));

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_INVALID_LENGTH,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenTwoMerchantCommissionsHaveSameKeyInOneRequest(): void
    {
        // Arrange
        $merchantCommission1Transfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommission2Transfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommission2Transfer->setKey($merchantCommission1Transfer->getKeyOrFail());

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommission1Transfer)
            ->addMerchantCommission($merchantCommission2Transfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_IS_NOT_UNIQUE,
        );

        $this->assertCount(2, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());

        $merchantCommission1Transfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->offsetGet(0);
        $this->assertNull($merchantCommission1Transfer->getUuid());
        $this->assertNull($merchantCommission1Transfer->getIdMerchantCommission());

        $merchantCommission2Transfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->offsetGet(1);
        $this->assertNull($merchantCommission2Transfer->getUuid());
        $this->assertNull($merchantCommission2Transfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenProvidedMerchantCommissionGroupDoesNotExist(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $merchantCommissionTransfer = (new MerchantCommissionBuilder([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => [
                MerchantCommissionGroupTransfer::UUID => 'non-existing-merchant-commission-group-uuid',
            ],
            MerchantCommissionTransfer::STORE_RELATION => [
                StoreRelationTransfer::STORES => [
                    [StoreTransfer::NAME => $storeTransfer->getNameOrFail()],
                ],
            ],
        ]))->build();

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_GROUP_DOES_NOT_EXIST,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenProvidedMerchantDoesNotExist(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->addMerchant(
            (new MerchantTransfer())->setMerchantReference('non-existing-merchant-reference'),
        );

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_DOES_NOT_EXIST,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenNameExceedsLengthLimit(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setName(str_repeat('a', 256));

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_NAME_INVALID_LENGTH,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenPriorityExceedsRangeLimit(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setPriority(10000);

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_PRIORITY_NOT_IN_RANGE,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenProvidedStoreDoesNotExist(): void
    {
        // Arrange
        $merchantCommissionGroupTransfer = $this->tester->haveMerchantCommissionGroup();
        $merchantCommissionTransfer = (new MerchantCommissionBuilder([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => [
                MerchantCommissionGroupTransfer::UUID => $merchantCommissionGroupTransfer->getUuidOrFail(),
            ],
            MerchantCommissionTransfer::STORE_RELATION => [
                StoreRelationTransfer::STORES => [
                    [StoreTransfer::NAME => 'XX'],
                ],
            ],
        ]))->build();

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenValidFromIsNotValidDateTime(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setValidFrom('not-a-datetime');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALID_FROM_INVALID_DATETIME,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenValidToIsNotValidDateTime(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setValidTo('not-a-datetime');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALID_TO_INVALID_DATETIME,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenValidToDateIsEarlierThanValidFromDate(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setValidFrom((new DateTime('+1 day'))->format('Y-m-d H:i:s'));
        $merchantCommissionTransfer->setValidTo((new DateTime('-1 day'))->format('Y-m-d H:i:s'));

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALIDITY_PERIOD_INVALID,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorForAllInvalidTransfersWhenRequestIsNotTransactional(): void
    {
        // Arrange
        $merchantCommission1Transfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommission1Transfer->setValidFrom('not-a-datetime');

        $merchantCommission2Transfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommission2Transfer->setValidTo('not-a-datetime');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addMerchantCommission($merchantCommission1Transfer)
            ->addMerchantCommission($merchantCommission2Transfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->assertCount(2, $merchantCommissionCollectionResponseTransfer->getErrors());
        $this->assertCount(2, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());

        $merchantCommissionCollectionIterator = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator();
        $this->assertNull($merchantCommissionCollectionIterator->current()->getUuid());
        $this->assertNull($merchantCommissionCollectionIterator->current()->getIdMerchantCommission());

        $merchantCommissionCollectionIterator->next();
        $this->assertNull($merchantCommissionCollectionIterator->current()->getUuid());
        $this->assertNull($merchantCommissionCollectionIterator->current()->getIdMerchantCommission());

        $errorCollectionIterator = $merchantCommissionCollectionResponseTransfer->getErrors()->getIterator();
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALID_FROM_INVALID_DATETIME,
            $errorCollectionIterator->current()->getMessage(),
        );

        $errorCollectionIterator->next();
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALID_TO_INVALID_DATETIME,
            $errorCollectionIterator->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenProvidedCalculatorTypePluginIsMissing(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setCalculatorTypePlugin('non-existing-calculator-type-plugin');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_VALIDATION_CALCULATOR_TYPE_PLUGIN_MISSING,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenOrderConditionContainsIncorrectCompareOperator(): void
    {
        // Arrange
        $this->tester->addOrderDecisionRulePluginToDependencies();
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setOrderCondition('test-order-field >= "test"');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_INVALID_COMPARE_OPERATOR_VALUE,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenOrderConditionContainsIncorrectQueryString(): void
    {
        // Arrange
        $this->tester->addOrderDecisionRulePluginToDependencies();
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setOrderCondition('test-order-field ? "test"');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_INVALID_QUERY_STRING,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenItemConditionContainsIncorrectCompareOperator(): void
    {
        // Arrange
        $this->tester->addOrderItemCollectorRulePluginToDependencies();
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setItemCondition('test-order-item-field >= "test"');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_INVALID_COMPARE_OPERATOR_VALUE,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }

    /**
     * @return void
     */
    public function testReturnValidationErrorWhenItemConditionContainsIncorrectQueryString(): void
    {
        // Arrange
        $this->tester->addOrderItemCollectorRulePluginToDependencies();
        $merchantCommissionTransfer = $this->tester->createMerchantCommissionTransfer();
        $merchantCommissionTransfer->setItemCondition('test-order-item-field = test');

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantCommission($merchantCommissionTransfer);

        // Act
        $merchantCommissionCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        // Assert
        $this->tester->assertValidationErrorsContainSingleMessageEqualTo(
            $merchantCommissionCollectionResponseTransfer->getErrors(),
            static::GLOSSARY_KEY_INVALID_QUERY_STRING,
        );

        $this->assertCount(1, $merchantCommissionCollectionResponseTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionResponseTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertNull($merchantCommissionTransfer->getUuid());
        $this->assertNull($merchantCommissionTransfer->getIdMerchantCommission());
    }
}
