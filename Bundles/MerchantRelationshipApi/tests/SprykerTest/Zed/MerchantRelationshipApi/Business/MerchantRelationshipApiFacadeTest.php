<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ApiDataBuilder;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiValidationErrorTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipApiTransfer;
use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationshipApi
 * @group Business
 * @group Facade
 * @group MerchantRelationshipApiFacadeTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipApiFacadeTest extends Unit
{
    /**
     * @var int
     */
    protected const NON_EXISTING_MERCHANT_RELATIONSHIP_ID = 9999999;

    /**
     * @var int
     */
    protected const TEST_COMPANY_BUSINESS_UNIT_ID = 999;

    /**
     * @uses \Spryker\Zed\MerchantRelationshipApi\Business\Request\MerchantRelationshipRequestDataInterface::KEY_MERCHANT_REFERENCE
     *
     * @var string
     */
    protected const KEY_MERCHANT_REFERENCE = 'merchantReference';

    /**
     * @uses \Spryker\Zed\MerchantRelationshipApi\Business\Request\MerchantRelationshipRequestDataInterface::KEY_ID_BUSINESS_UNIT_OWNER
     *
     * @var string
     */
    public const KEY_ID_BUSINESS_UNIT_OWNER = 'idBusinessUnitOwner';

    /**
     * @var \SprykerTest\Zed\MerchantRelationshipApi\MerchantRelationshipApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetMerchantRelationshipWillReturnCorrectMerchantRelationship(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $idMerchantRelationship = $merchantRelationshipTransfer->getIdMerchantRelationship();

        // Act
        $apiItemTransfer = $this->tester->getFacade()->getMerchantRelationship($idMerchantRelationship);

        // Assert
        $this->assertSame((string)$idMerchantRelationship, $apiItemTransfer->getId(), 'Returned ID should be equal to requested');
        $this->assertArrayHasKey(
            MerchantRelationshipApiTransfer::ID_MERCHANT_RELATIONSHIP,
            $apiItemTransfer->getData(),
            'Response data should contain `idMerchantRelationship`',
        );
        $this->assertSame(
            $idMerchantRelationship,
            $apiItemTransfer->getData()[MerchantRelationshipApiTransfer::ID_MERCHANT_RELATIONSHIP],
            'Returned ID should be equal to requested',
        );
    }

    /**
     * @return void
     */
    public function testGetNonExistingMerchantRelationshipWillReturnEmptyData(): void
    {
        // Act
        $apiItemTransfer = $this->tester->getFacade()->getMerchantRelationship(
            static::NON_EXISTING_MERCHANT_RELATIONSHIP_ID,
        );

        // Assert
        $this->assertEmpty($apiItemTransfer->getData());
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionWithoutAnyFilteringWillReturnPaginatedCollection(): void
    {
        // Arrange
        $this->tester->ensureRequiredNumberOdMerchantRelationshipsExist();
        $apiFilterTransfer = (new ApiFilterTransfer())
            ->setOffset(10)
            ->setLimit(10);
        $apiRequestTransfer = (new ApiRequestTransfer())->setFilter($apiFilterTransfer);

        // Act
        $apiCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationshipCollection($apiRequestTransfer);

        // Assert
        $this->assertNotEmpty($apiCollectionTransfer->getData());
        $this->assertSame(2, $apiCollectionTransfer->getPagination()->getPage());
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionFilteringByIdsWillReturnFilteredCollection(): void
    {
        // Arrange
        $merchantRelationshipIds = [
            $this->tester->createMerchantRelationship()->getIdMerchantRelationship(),
            $this->tester->createMerchantRelationship()->getIdMerchantRelationship(),
        ];
        $apiFilterTransfer = (new ApiFilterTransfer())
            ->setCriteriaJson(json_encode([MerchantRelationshipConditionsTransfer::MERCHANT_RELATIONSHIP_IDS => $merchantRelationshipIds]));
        $apiRequestTransfer = (new ApiRequestTransfer())->setFilter($apiFilterTransfer);

        // Act
        $apiCollectionTransfer = $this->tester->getFacade()->getMerchantRelationshipCollection($apiRequestTransfer);

        // Assert
        $this->assertSame(
            $merchantRelationshipIds,
            array_column($apiCollectionTransfer->getData(), MerchantRelationshipApiTransfer::ID_MERCHANT_RELATIONSHIP),
        );
        $this->assertNotEmpty($apiCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionSortingByNameDescWillReturnSortedCollection(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship([
            MerchantRelationshipTransfer::NAME => 'ZZZ',
        ]);
        $apiRequestTransfer = $this->tester->createdSortingApiRequestTransfer([
            'Merchant.name' => '-',
        ]);

        // Act
        $apiCollectionTransfer = $this->tester->getFacade()->getMerchantRelationshipCollection($apiRequestTransfer);

        // Assert
        $this->assertCollectionOrder($merchantRelationshipTransfer, $apiCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionSortingByNameAscWillReturnSortedCollection(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship([
            MerchantRelationshipTransfer::NAME => 'AAA',
        ]);
        $apiRequestTransfer = $this->tester->createdSortingApiRequestTransfer([
            'Merchant.name' => '',
        ]);

        // Act
        $apiCollectionTransfer = $this->tester->getFacade()->getMerchantRelationshipCollection($apiRequestTransfer);

        // Assert
        $this->assertCollectionOrder($merchantRelationshipTransfer, $apiCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWillCreateMerchantRelationshipSuccessfully(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $merchantTransfer = $this->tester->haveMerchant();
        $apiDataTransfer = (new ApiDataBuilder([
            ApiDataTransfer::DATA => [
                MerchantRelationshipApiTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
                MerchantRelationshipApiTransfer::ID_COMPANY => $companyBusinessUnitTransfer->getFkCompany(),
                MerchantRelationshipApiTransfer::ID_BUSINESS_UNIT_OWNER => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            ],
        ]))->build();

        // Act
        $apiItemTransfer = $this->tester->getFacade()
            ->createMerchantRelationship($apiDataTransfer);

        // Assert
        $this->assertNotEmpty($apiItemTransfer->getId());
        $this->assertSame($merchantTransfer->getMerchantReference(), $apiItemTransfer->getData()[MerchantRelationshipApiTransfer::MERCHANT_REFERENCE]);
        $this->assertSame($companyBusinessUnitTransfer->getIdCompanyBusinessUnit(), $apiItemTransfer->getData()[MerchantRelationshipApiTransfer::ID_BUSINESS_UNIT_OWNER]);
        $this->assertSame($companyBusinessUnitTransfer->getFkCompany(), $apiItemTransfer->getData()[MerchantRelationshipApiTransfer::ID_COMPANY]);
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithNonExistingMerchantWillReturnError(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $idMerchantRelationship = $merchantRelationshipTransfer->getIdMerchantRelationship();

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $apiDataTransfer = (new ApiDataBuilder([
            ApiDataTransfer::DATA => [
                MerchantRelationshipApiTransfer::ID_MERCHANT_RELATIONSHIP => $idMerchantRelationship,
                MerchantRelationshipApiTransfer::MERCHANT_REFERENCE => null,
                MerchantRelationshipApiTransfer::ID_COMPANY => $companyBusinessUnitTransfer->getFkCompany(),
                MerchantRelationshipApiTransfer::ID_BUSINESS_UNIT_OWNER => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            ],
        ]))->build();

        // Act
        $apiItemTransfer = $this->tester->getFacade()->createMerchantRelationship($apiDataTransfer);

        // Assert
        $this->assertSame(
            sprintf('"%s" field is empty.', static::KEY_MERCHANT_REFERENCE),
            $apiItemTransfer->getValidationErrors()[0][ApiValidationErrorTransfer::MESSAGES][0],
        );
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithNonExistingCompanyBusinessUnitWillReturnError(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $merchantTransfer = $this->tester->haveMerchant();

        $apiDataTransfer = (new ApiDataBuilder([
        ApiDataTransfer::DATA => [
            MerchantRelationshipApiTransfer::ID_MERCHANT_RELATIONSHIP => $merchantRelationshipTransfer->getIdMerchantRelationship(),
            MerchantRelationshipApiTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
        ]]))->build();

        // Act
        $apiItemTransfer = $this->tester->getFacade()->createMerchantRelationship($apiDataTransfer);

        // Assert
        $this->assertSame(
            sprintf('"%s" field is not defined.', static::KEY_ID_BUSINESS_UNIT_OWNER),
            $apiItemTransfer->getValidationErrors()[0][ApiValidationErrorTransfer::MESSAGES][0],
        );
    }

    /**
     * @return void
     */
    public function testUpdateMerchantRelationshipWillUpdateMerchantRelationshipSuccessfully(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $merchantTransfer = $this->tester->haveMerchant();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit()->getFkCompany(),
        ]);

        $apiDataTransfer = (new ApiDataBuilder([
            ApiDataTransfer::DATA => [
                MerchantRelationshipApiTransfer::ID_MERCHANT_RELATIONSHIP => $merchantRelationshipTransfer->getIdMerchantRelationship(),
                MerchantRelationshipApiTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
                MerchantRelationshipApiTransfer::ID_COMPANY => $companyBusinessUnitTransfer->getFkCompany(),
                MerchantRelationshipApiTransfer::ID_BUSINESS_UNIT_OWNER => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            ],
        ]))->build();

        // Act
        $apiItemTransfer = $this->tester->getFacade()->updateMerchantRelationship(
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
            $apiDataTransfer,
        );

        // Assert
        $this->assertSame(
            (string)$merchantRelationshipTransfer->getIdMerchantRelationship(),
            $apiItemTransfer->getId(),
            'The merchant relationship id does not match',
        );

        $this->assertSame(
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            $apiItemTransfer->getData()[MerchantRelationshipApiTransfer::ID_BUSINESS_UNIT_OWNER],
            'Company business unit was not updated',
        );
    }

    /**
     * @group test
     *
     * @return void
     */
    public function testUpdateMerchantRelationshipWithNonExistingCompanyBusinessUnitWillReturnError(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $merchantTransfer = $this->tester->haveMerchant();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit(static::TEST_COMPANY_BUSINESS_UNIT_ID);

        $apiDataTransfer = (new ApiDataBuilder([
        ApiDataTransfer::DATA => [
            MerchantRelationshipApiTransfer::ID_MERCHANT_RELATIONSHIP => $merchantRelationshipTransfer->getIdMerchantRelationship(),
            MerchantRelationshipApiTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantRelationshipApiTransfer::ID_COMPANY => $companyBusinessUnitTransfer->getFkCompany(),
            MerchantRelationshipApiTransfer::ID_BUSINESS_UNIT_OWNER => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]]))->build();

        // Act
        $apiItemTransfer = $this->tester->getFacade()->updateMerchantRelationship(
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
            $apiDataTransfer,
        );

        // Assert
        $this->assertSame(
            sprintf('Can not find related company business unit by id "%s".', static::TEST_COMPANY_BUSINESS_UNIT_ID),
            $apiItemTransfer->getValidationErrors()[0][ApiValidationErrorTransfer::MESSAGES][0],
        );
    }

    /**
     * @return void
     */
    public function testUpdateNonExistingMerchantRelationshipWillReturnError(): void
    {
        $apiItemTransfer = $this->tester->getFacade()->updateMerchantRelationship(
            static::NON_EXISTING_MERCHANT_RELATIONSHIP_ID,
            new ApiDataTransfer(),
        );

        $this->assertSame(Response::HTTP_NOT_FOUND, $apiItemTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testValidateWillNotReturnErrorsWhenRequiredPropertiesAreSet(): void
    {
        // Arrange
        $apiDataTransfer = (new ApiDataBuilder([
            ApiDataTransfer::DATA => [
                MerchantRelationshipApiTransfer::MERCHANT_REFERENCE => 25,
                MerchantRelationshipApiTransfer::ID_COMPANY => 3,
                MerchantRelationshipApiTransfer::ID_BUSINESS_UNIT_OWNER => 123,
            ],
        ]))->build();
        $apiRequestTransfer = (new ApiRequestTransfer())
            ->setApiData($apiDataTransfer)
            ->setRequestType(Request::METHOD_POST);

        // Act
        $apiValidationErrorTransfers = $this->tester->getFacade()->validateMerchantRelationshipRequestData($apiRequestTransfer);

        // Assert
        $this->assertEmpty($apiValidationErrorTransfers);
    }

    /**
     * @return void
     */
    public function testValidateWillReturnErrorsIfRequestDataIsEmpty(): void
    {
        // Arrange
        $apiRequestTransfer = (new ApiRequestTransfer())->setRequestType(Request::METHOD_POST);

        // Act
        $apiValidationErrorTransfers = $this->tester->getFacade()->validateMerchantRelationshipRequestData($apiRequestTransfer);

        // Assert
        $this->assertNotEmpty($apiValidationErrorTransfers);
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationshipWillRemoveExistingMerchantRelationship(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();

        // Act
        $apiItemTransfer = $this->tester->getFacade()->deleteMerchantRelationship(
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
        );

        // Assert
        $this->assertNull($apiItemTransfer->getId());
        $this->assertSame(Response::HTTP_NO_CONTENT, $apiItemTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testDeleteWillDoNothingWithNonExistingMerchantRelationship(): void
    {
        // Act
        $apiItemTransfer = $this->tester->getFacade()->deleteMerchantRelationship(static::NON_EXISTING_MERCHANT_RELATIONSHIP_ID);

        // Assert
        $this->assertSame(Response::HTTP_NO_CONTENT, $apiItemTransfer->getStatusCode());
        $this->assertNull($apiItemTransfer->getId());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\ApiCollectionTransfer $apiCollectionTransfer
     *
     * @return void
     */
    protected function assertCollectionOrder(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ApiCollectionTransfer $apiCollectionTransfer
    ): void {
        $this->assertSame(
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
            $apiCollectionTransfer->getData()[0][MerchantRelationshipApiTransfer::ID_MERCHANT_RELATIONSHIP],
            'The id that should be the first record does not match',
        );

        $this->assertSame(
            $merchantRelationshipTransfer->getMerchant()->getName(),
            $apiCollectionTransfer->getData()[0][MerchantRelationshipApiTransfer::MERCHANT_NAME],
            'Wrong sorting',
        );
    }
}
