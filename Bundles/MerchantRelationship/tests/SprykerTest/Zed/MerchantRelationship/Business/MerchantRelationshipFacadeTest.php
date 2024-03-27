<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Business\MerchantRelationship;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortCollectionTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipBusinessFactory;
use Spryker\Zed\MerchantRelationship\MerchantRelationshipDependencyProvider;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipPersistenceFactory;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepository;
use Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostDeletePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Business
 * @group MerchantRelationship
 * @group Facade
 * @group MerchantRelationshipFacadeTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const MR_KEY_1_TEST = 'mr-test-1';

    /**
     * @var string
     */
    protected const MR_KEY_2_TEST = 'mr-test-2';

    /**
     * @var string
     */
    protected const BU_OWNER_KEY_OWNER = 'unit-owner';

    /**
     * @var string
     */
    protected const BU_KEY_UNIT_1 = 'unit-1';

    /**
     * @var string
     */
    protected const BU_KEY_UNIT_2 = 'unit-2';

    /**
     * @uses \Spryker\Zed\MerchantRelationshipProductListGui\Communication\Mapper\ProductListUsedByTableMapper::ENTITY_TITLE
     *
     * @var string
     */
    protected const MR_ENTITY_TITLE = 'Merchant Relationship';

    /**
     * @var \SprykerTest\Zed\MerchantRelationship\MerchantRelationshipBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateMerchantRelationship(): void
    {
        $merchantRelationship = $this->tester->createMerchantRelationship(static::MR_KEY_1_TEST);

        // Assert
        $this->assertNotNull($merchantRelationship->getIdMerchantRelationship());
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipForwardCompatibility(): void
    {
        // Arrange
        $merchantRelationshipRequestTransfer = $this->tester->createMerchantRelationshipRequest(static::MR_KEY_1_TEST);
        $merchantRelationshipTransfer = $merchantRelationshipRequestTransfer->getMerchantRelationship();

        // Act
        $merchantRelationshipResponseTransfer = $this->tester->getFacade()->createMerchantRelationship(
            $merchantRelationshipTransfer,
            $merchantRelationshipRequestTransfer,
        );

        // Assert
        $this->assertNotNull($merchantRelationshipResponseTransfer->getMerchantRelationship());
        $this->assertNotNull($merchantRelationshipResponseTransfer->getMerchantRelationship()->getIdMerchantRelationship());
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithNotUniqueKeyHasErrorsInResponse(): void
    {
        // Arrange
        $merchantRelationshipRequestTransfer = $this->tester->createMerchantRelationshipRequest(static::MR_KEY_1_TEST);
        $merchantRelationshipTransfer = $merchantRelationshipRequestTransfer->getMerchantRelationship();
        $merchantRelationshipTransfer = clone $merchantRelationshipTransfer;
        $merchantRelationshipTransfer->setIdMerchantRelationship(null);
        $merchantRelationshipRequestTransfer->setMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $merchantRelationshipResponseTransfer = $this->tester->getFacade()->createMerchantRelationship(
            new MerchantRelationshipTransfer(),
            $merchantRelationshipRequestTransfer,
        );

        // Assert
        $this->assertFalse($merchantRelationshipResponseTransfer->getIsSuccessfulOrFail());
        $this->assertCount(1, $merchantRelationshipResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\MerchantRelationshipErrorTransfer $merchantRelationshipErrorTransfer */
        $merchantRelationshipErrorTransfer = $merchantRelationshipResponseTransfer->getErrors()->offsetGet(0);
        $this->assertSame(MerchantRelationshipTransfer::MERCHANT_RELATIONSHIP_KEY, $merchantRelationshipErrorTransfer->getField());
        $this->assertSame(
            sprintf('Merchant relationship key "%s" already exists.', $merchantRelationshipTransfer->getMerchantRelationshipKey()),
            $merchantRelationshipErrorTransfer->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithNotUniqueKeyThrowsException(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship(static::MR_KEY_1_TEST);
        $newMerchantRelationshipTransfer = clone $merchantRelationshipTransfer;
        $newMerchantRelationshipTransfer->setIdMerchantRelationship(null);

        $this->expectException(Exception::class);

        // Act
        $this->tester->getFacade()->createMerchantRelationship($newMerchantRelationshipTransfer);
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithOwner(): void
    {
        // Arrange
        $merchantRelationship = $this->tester->createMerchantRelationship(static::MR_KEY_1_TEST, static::BU_OWNER_KEY_OWNER);

        // Assert
        $this->assertNotNull($merchantRelationship->getIdMerchantRelationship());
        $this->assertSame(
            $merchantRelationship->getOwnerCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
            $merchantRelationship->getFkCompanyBusinessUnit(),
        );
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithOneAssignee(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::KEY => static::BU_OWNER_KEY_OWNER,
        ]);
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantRelationshipTransfer = (new MerchantRelationshipTransfer())
            ->setMerchantRelationshipKey(static::MR_KEY_1_TEST)
            ->setOwnerCompanyBusinessUnit($companyBusinessUnitTransfer)
            ->setFkCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            ->setFkMerchant($merchantTransfer->getIdMerchant())
            ->setAssigneeCompanyBusinessUnits(
                (new CompanyBusinessUnitCollectionTransfer())
                    ->addCompanyBusinessUnit($companyBusinessUnitTransfer),
            );

        // Act
        $this->tester->getFacade()
            ->createMerchantRelationship($merchantRelationshipTransfer);

        // Assert
        $this->assertNotNull($merchantRelationshipTransfer->getIdMerchantRelationship());
        $this->assertNotNull($merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits());
        $this->assertCount(1, $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits());
        $this->assertSame(static::BU_OWNER_KEY_OWNER, $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits()[0]->getKey());
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithFewAssignee(): void
    {
        // Arrange
        $merchantRelationship = $this->tester->createMerchantRelationship(
            static::MR_KEY_1_TEST,
            static::BU_OWNER_KEY_OWNER,
            [static::BU_OWNER_KEY_OWNER, static::BU_KEY_UNIT_1, static::BU_KEY_UNIT_2],
        );

        // Assert
        $this->assertNotNull($merchantRelationship->getIdMerchantRelationship());
        $this->assertNotNull($merchantRelationship->getAssigneeCompanyBusinessUnits());
        $this->assertCount(3, $merchantRelationship->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits());
    }

    /**
     * @return void
     */
    public function testUpdateMerchantRelationship(): void
    {
        // Arrange
        $merchantRelationship = $this->tester->createMerchantRelationship(static::MR_KEY_1_TEST);
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        $newMerchant = $this->tester->haveMerchant();
        $newCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $newKey = 'mr-test-1';

        $merchantRelationship
            ->setFkMerchant($newMerchant->getIdMerchant())
            ->setFkCompanyBusinessUnit($newCompanyBusinessUnit->getIdCompanyBusinessUnit())
            ->setMerchantRelationshipKey($newKey);

        // Act
        $updatedMerchantRelationship = $this->tester->getFacade()
            ->updateMerchantRelationship($merchantRelationship);

        // Assert
        $this->assertSame($idMerchantRelationship, $updatedMerchantRelationship->getIdMerchantRelationship());
        $this->assertSame($newMerchant->getIdMerchant(), $updatedMerchantRelationship->getFkMerchant());
        $this->assertSame($newCompanyBusinessUnit->getIdCompanyBusinessUnit(), $updatedMerchantRelationship->getFkCompanyBusinessUnit());
        $this->assertSame($newKey, $updatedMerchantRelationship->getMerchantRelationshipKey());
    }

    /**
     * @return void
     */
    public function testUpdateMerchantRelationshipWithForwardCompatibility(): void
    {
        // Arrange
        $merchantRelationship = $this->tester->createMerchantRelationship(static::MR_KEY_1_TEST);
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        $newMerchant = $this->tester->haveMerchant();
        $newCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $newKey = 'mr-test-1';

        $merchantRelationship
            ->setIdMerchantRelationship($idMerchantRelationship)
            ->setFkMerchant($newMerchant->getIdMerchant())
            ->setFkCompanyBusinessUnit($newCompanyBusinessUnit->getIdCompanyBusinessUnit())
            ->setMerchantRelationshipKey($newKey);

        $merchantRelationshipRequestTransfer = new MerchantRelationshipRequestTransfer();
        $merchantRelationshipRequestTransfer->setMerchantRelationship($merchantRelationship);

        // Act
        $updatedMerchantRelationship = $this->tester->getFacade()
            ->updateMerchantRelationship(
                $merchantRelationship,
                $merchantRelationshipRequestTransfer,
            )
            ->getMerchantRelationship();

        // Assert
        $this->assertSame($idMerchantRelationship, $updatedMerchantRelationship->getIdMerchantRelationship());
        $this->assertSame($newMerchant->getIdMerchant(), $updatedMerchantRelationship->getFkMerchant());
        $this->assertSame($newCompanyBusinessUnit->getIdCompanyBusinessUnit(), $updatedMerchantRelationship->getFkCompanyBusinessUnit());
        $this->assertSame($newKey, $updatedMerchantRelationship->getMerchantRelationshipKey());
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipById(): void
    {
        // Arrange
        $expectedMerchantRelationship = $this->tester->createMerchantRelationship(static::MR_KEY_1_TEST);
        $expectedMerchantRelationship->setName(
            sprintf('%s - %s', $expectedMerchantRelationship->getIdMerchantRelationship(), $expectedMerchantRelationship->getOwnerCompanyBusinessUnit()->getName()),
        );

        $merchantRelationship = (new MerchantRelationshipTransfer())
            ->setIdMerchantRelationship(
                $expectedMerchantRelationship->getIdMerchantRelationship(),
            );

        // Act
        $actualMerchantRelationship = $this->tester->getFacade()
            ->getMerchantRelationshipById($merchantRelationship);

        // Assert
        $this->assertSame($expectedMerchantRelationship->getIdMerchantRelationship(), $actualMerchantRelationship->getIdMerchantRelationship());
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationship(): void
    {
        // Arrange
        $merchantRelationship = $this->tester->createMerchantRelationship(static::MR_KEY_1_TEST);
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        // Act
        $this->tester->getFacade()
            ->deleteMerchantRelationship($merchantRelationship);

        // Assert
        $this->tester->assertMerchantRelationshipDoesNotExist($idMerchantRelationship);
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationshipForwardCompatible(): void
    {
        // Arrange
        $merchantRelationship = $this->tester->createMerchantRelationship(static::MR_KEY_1_TEST);
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();
        $merchantRelationshipRequestTransfer = (new MerchantRelationshipRequestTransfer())
            ->setMerchantRelationship($merchantRelationship);

        // Act
        $this->tester->getFacade()
            ->deleteMerchantRelationship($merchantRelationship, $merchantRelationshipRequestTransfer);

        // Assert
        $this->tester->assertMerchantRelationshipDoesNotExist($idMerchantRelationship);
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationshipWithAssigneeDeletesAssignee(): void
    {
        // Arrange
        $merchantRelationship = $this->tester->createMerchantRelationship(
            static::MR_KEY_1_TEST,
            static::BU_OWNER_KEY_OWNER,
            [static::BU_OWNER_KEY_OWNER, static::BU_KEY_UNIT_1, static::BU_KEY_UNIT_2],
        );
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        // Act
        $this->tester->getFacade()->deleteMerchantRelationship(
            (new MerchantRelationshipTransfer())->setIdMerchantRelationship($idMerchantRelationship),
        );

        // Assert
        $this->tester->assertMerchantRelationshipToCompanyBusinessUnitDoesNotExist($idMerchantRelationship);
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationshipExecutesAStackOfPostDeletePlugins(): void
    {
        // Assert
        $merchantRelationshipPostDeletePluginMock = $this->getMockBuilder(MerchantRelationshipPostDeletePluginInterface::class)
            ->getMock();
        $merchantRelationshipPostDeletePluginMock
            ->expects($this->once())
            ->method('execute');

        $this->tester->setDependency(MerchantRelationshipDependencyProvider::PLUGINS_MERCHANT_RELATIONSHIP_POST_DELETE, [
            $merchantRelationshipPostDeletePluginMock,
        ]);

        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship(
            static::MR_KEY_1_TEST,
            static::BU_OWNER_KEY_OWNER,
            [static::BU_OWNER_KEY_OWNER, static::BU_KEY_UNIT_1, static::BU_KEY_UNIT_2],
        );
        $merchantRelationshipRequestTransfer = (new MerchantRelationshipRequestTransfer())
            ->setMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $this->tester->getFacade()->deleteMerchantRelationship(
            $merchantRelationshipTransfer,
            $merchantRelationshipRequestTransfer,
        );
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionWillReturnAllAvailableRelationships(): void
    {
        // Arrange
        $merchantRelationshipConfigMock = $this->tester->mockConfigMethod('getDefaultPaginationLimit', 1000);

        $merchantRelationshipPersistenceFactory = (new MerchantRelationshipPersistenceFactory())->setConfig($merchantRelationshipConfigMock);
        $merchantRelationshipRepository = (new MerchantRelationshipRepository())->setFactory($merchantRelationshipPersistenceFactory);
        $merchantRelationshipBusinessFactory = (new MerchantRelationshipBusinessFactory())->setRepository($merchantRelationshipRepository);
        $merchantRelationshipFacade = $this->tester->getFacade()->setFactory($merchantRelationshipBusinessFactory);

        $this->tester->createMerchantRelationship(static::MR_KEY_1_TEST);

        // Act
        $merchantRelationTransfers = $merchantRelationshipFacade->getMerchantRelationshipCollection();

        // Assert
        $this->assertCount($this->tester->getMerchantRelationsCount(), $merchantRelationTransfers);
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionWillReturnRelationshipsFilteredByIds(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship(static::MR_KEY_1_TEST);
        $merchantRelationshipFilterTransfer = (new MerchantRelationshipFilterTransfer())->setMerchantRelationshipIds(
            [$merchantRelationshipTransfer->getIdMerchantRelationship()],
        );

        // Act
        $merchantRelationTransfers = $this->tester
            ->getFacade()
            ->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationTransfers);
    }

    /**
     * @return void
     */
    public function testGetFilteredMerchantRelationshipCollection(): void
    {
        // Arrange
        $this->tester->createMerchantRelationships(30);
        $merchantRelationshipFilterTransfer = (new MerchantRelationshipFilterTransfer())
            ->setLimit(10);

        // Act
        $merchantRelationshipTransfers = $this->tester->getFacade()
            ->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);

        // Assert
        $this->assertNotEmpty($merchantRelationshipTransfers);
        $this->assertCount(10, $merchantRelationshipTransfers);
    }

    /**
     * @return void
     */
    public function testGetFilteredMerchantRelationshipCollectionShouldReturnEmptyCollectionWhenOutOfBounds(): void
    {
        // Arrange
        $merchantRelationshipFilterTransfer = (new MerchantRelationshipFilterTransfer())
            ->setOffset(100000)
            ->setLimit(10);

        // Act
        $merchantRelationshipTransfers = $this->tester->getFacade()
            ->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);

        // Assert
        $this->assertCount(0, $merchantRelationshipTransfers, 'The collection should be empty');
    }

    /**
     * @return void
     */
    public function testGetFilteredMerchantRelationshipCollectionFilteredByIdMerchantRelationship(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship(static::MR_KEY_1_TEST);

        $merchantRelationshipFilterTransfer = (new MerchantRelationshipFilterTransfer())
            ->setLimit(10)
            ->setMerchantRelationshipIds(
                [$merchantRelationshipTransfer->getIdMerchantRelationship()],
            );

        // Act
        $merchantRelationshipTransfers = $this->tester->getFacade()->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationshipTransfers);
        $this->assertSame(
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
            $merchantRelationshipTransfers[0]->getIdMerchantRelationship(),
        );
    }

    /**
     * @return void
     */
    public function testGetFilteredMerchantRelationshipCollectionWithSortingAsc(): void
    {
        // Arrange
        $this->tester->createMerchantRelationshipsForSorting(5);
        $merchantRelationshipFilterTransfer = (new MerchantRelationshipFilterTransfer())
            ->setLimit(5)
            ->setSortBy([
                'Merchant.name' => 'ASC',
                'Merchant.status' => 'ASC',
            ]);

        // Act
        $merchantRelationshipTransfers = $this->tester->getFacade()->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);

        // Assert
        $this->assertCount(5, $merchantRelationshipTransfers);
        $this->assertSame('AAA-1', $merchantRelationshipTransfers[0]->getMerchantRelationshipKey());
        $this->assertSame('AAA-2', $merchantRelationshipTransfers[1]->getMerchantRelationshipKey());
        $this->assertSame('AAA-3', $merchantRelationshipTransfers[2]->getMerchantRelationshipKey());
        $this->assertSame('AAA-4', $merchantRelationshipTransfers[3]->getMerchantRelationshipKey());
        $this->assertSame('AAA-5', $merchantRelationshipTransfers[4]->getMerchantRelationshipKey());
    }

    /**
     * @return void
     */
    public function testGetFilteredMerchantRelationshipCollectionWithSortingDesc(): void
    {
        // Arrange
        $this->tester->createMerchantRelationshipsForSorting(5, 'DESC');
        $merchantRelationshipFilterTransfer = (new MerchantRelationshipFilterTransfer())
            ->setLimit(5)
            ->setSortBy([
                'Merchant.name' => 'DESC',
                'Merchant.status' => 'DESC',
            ]);

        // Act
        $merchantRelationshipTransfers = $this->tester->getFacade()->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);

        // Assert
        $this->assertCount(5, $merchantRelationshipTransfers);
        $this->assertSame('ZZZ-5', $merchantRelationshipTransfers[0]->getMerchantRelationshipKey());
        $this->assertSame('ZZZ-4', $merchantRelationshipTransfers[1]->getMerchantRelationshipKey());
        $this->assertSame('ZZZ-3', $merchantRelationshipTransfers[2]->getMerchantRelationshipKey());
        $this->assertSame('ZZZ-2', $merchantRelationshipTransfers[3]->getMerchantRelationshipKey());
        $this->assertSame('ZZZ-1', $merchantRelationshipTransfers[4]->getMerchantRelationshipKey());
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionByCriteriaTransfer(): void
    {
        // Arrange
        $this->tester->createMerchantRelationships(30);
        $paginationTransfer = (new PaginationTransfer())
            ->setFirstIndex(10)
            ->setMaxPerPage(10);
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationshipCollection(
                null,
                $merchantRelationshipCriteriaTransfer,
            );

        // Assert
        $this->assertGreaterThan(0, $merchantRelationshipCollectionTransfer->getMerchantRelationships()->count());
        $paginationTransfer = $merchantRelationshipCollectionTransfer->getPagination();
        $this->assertNotEmpty($paginationTransfer);
        $this->assertSame(2, $paginationTransfer->getPage());
        $this->assertSame(10, $paginationTransfer->getMaxPerPage());
        $this->assertSame($this->tester->getMerchantRelationsCount(), $paginationTransfer->getNbResults());
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionByCriteriaShouldReturnEmptyCollectionWhenOutOfBounds(): void
    {
        // Arrange
        $paginationTransfer = (new PaginationTransfer())
            ->setFirstIndex(10000)
            ->setMaxPerPage(10);
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);

        // Assert
        $this->assertCount(0, $merchantRelationshipCollectionTransfer->getMerchantRelationships(), 'The collection should be empty');
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionByCriteriaFilteredByIdMerchantRelationship(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship(static::MR_KEY_1_TEST);

        $paginationTransfer = (new PaginationTransfer())
            ->setMaxPerPage(10);
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->setMerchantRelationshipIds([$merchantRelationshipTransfer->getIdMerchantRelationship()]);
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer)
            ->setPagination($paginationTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationshipCollectionTransfer->getMerchantRelationships());
        $this->assertSame(
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
            $merchantRelationshipCollectionTransfer->getMerchantRelationships()[0]->getIdMerchantRelationship(),
        );
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionByCriteriaWithSortingAsc(): void
    {
        // Arrange
        $this->tester->createMerchantRelationshipsForSorting(5);

        $paginationTransfer = (new PaginationTransfer())
            ->setMaxPerPage(5);
        $sortCollectionTransfer = (new SortCollectionTransfer())
            ->addSort((new SortTransfer())
                ->setField('Merchant.name')
                ->setIsAscending(true))
            ->addSort((new SortTransfer())
                ->setField('Merchant.status')
                ->setIsAscending(true));

        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setPagination($paginationTransfer)
            ->setSortCollection($sortCollectionTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);

        // Assert
        $this->assertCount(5, $merchantRelationshipCollectionTransfer->getMerchantRelationships());
        $merchantRelationships = $merchantRelationshipCollectionTransfer->getMerchantRelationships();

        $this->assertSame('AAA-1', $merchantRelationships[0]->getMerchantRelationshipKey());
        $this->assertSame('AAA-2', $merchantRelationships[1]->getMerchantRelationshipKey());
        $this->assertSame('AAA-3', $merchantRelationships[2]->getMerchantRelationshipKey());
        $this->assertSame('AAA-4', $merchantRelationships[3]->getMerchantRelationshipKey());
        $this->assertSame('AAA-5', $merchantRelationships[4]->getMerchantRelationshipKey());
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionByCriteriaWithSortingDesc(): void
    {
        // Arrange
        $this->tester->createMerchantRelationshipsForSorting(5, 'DESC');

        $paginationTransfer = (new PaginationTransfer())
            ->setMaxPerPage(5);
        $sortCollectionTransfer = (new SortCollectionTransfer())
            ->addSort((new SortTransfer())
                ->setField('Merchant.name')
                ->setIsAscending(false))
            ->addSort((new SortTransfer())
                ->setField('Merchant.status')
                ->setIsAscending(false));

        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setPagination($paginationTransfer)
            ->setSortCollection($sortCollectionTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);

        // Assert
        $this->assertCount(5, $merchantRelationshipCollectionTransfer->getMerchantRelationships());
        $merchantRelationships = $merchantRelationshipCollectionTransfer->getMerchantRelationships();

        $this->assertSame('ZZZ-5', $merchantRelationships[0]->getMerchantRelationshipKey());
        $this->assertSame('ZZZ-4', $merchantRelationships[1]->getMerchantRelationshipKey());
        $this->assertSame('ZZZ-3', $merchantRelationships[2]->getMerchantRelationshipKey());
        $this->assertSame('ZZZ-2', $merchantRelationships[3]->getMerchantRelationshipKey());
        $this->assertSame('ZZZ-1', $merchantRelationships[4]->getMerchantRelationshipKey());
    }
}
