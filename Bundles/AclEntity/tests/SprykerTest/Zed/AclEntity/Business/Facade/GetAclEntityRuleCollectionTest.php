<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclEntity\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityRuleCriteriaConditionsTransfer;
use Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Shared\AclEntity\AclEntityConstants;
use SprykerTest\Zed\AclEntity\AclEntityBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclEntity
 * @group Business
 * @group Facade
 * @group GetAclEntityRuleCollectionTest
 * Add your own group annotations below this line
 */
class GetAclEntityRuleCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const ACL_ROLE_TEST_NAME = 'ACL_ROLE_TEST_NAME';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_TARGET_ENTITY = 'Orm\Zed\Merchant\Persistence\SpyMerchant';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_TARGET_ENTITY_TWO = 'Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder';

    /**
     * @var \SprykerTest\Zed\AclEntity\AclEntityBusinessTester
     */
    protected AclEntityBusinessTester $tester;

    /**
     * @var \Generated\Shared\Transfer\RolesTransfer
     */
    protected $roleTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureAclEntityRuleDatabaseIsEmpty();
        $this->tester->ensureAclRoleDatabaseIsEmpty();

        $this->roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => static::ACL_ROLE_TEST_NAME]);
    }

    /**
     * @return void
     */
    public function testReturnsCorrectAclEntityRuleByIdAclEntityRule(): void
    {
        // Arrange
        $aclEntityRuleTransfer = $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
            ],
        );

        $aclEntityRuleCriteriaTransfer = (new AclEntityRuleCriteriaTransfer())
            ->setAclEntityRuleCriteriaConditions(
                (new AclEntityRuleCriteriaConditionsTransfer())->addIdAclEntityRule($aclEntityRuleTransfer->getIdAclEntityRuleOrFail()),
            );

        // Act
        $aclEntityRuleCollectionTransfer = $this->tester->getFacade()->getAclEntityRuleCollection($aclEntityRuleCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclEntityRuleCollectionTransfer->getAclEntityRules());
        $this->assertSameAclEntityRuleTransfer($aclEntityRuleTransfer, $aclEntityRuleCollectionTransfer->getAclEntityRules()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testReturnsCorrectAclEntityRuleByIdAclEntitySegment(): void
    {
        // Arrange
        $aclEntitySegmentTransfer = $this->tester->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
                AclEntitySegmentRequestTransfer::NAME => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
            ],
        );

        $aclEntityRuleTransfer = $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentTransfer->getIdAclEntitySegmentOrFail(),
            ],
        );

        $aclEntityRuleCriteriaTransfer = (new AclEntityRuleCriteriaTransfer())
            ->setAclEntityRuleCriteriaConditions(
                (new AclEntityRuleCriteriaConditionsTransfer())->addIdAclEntitySegment($aclEntityRuleTransfer->getIdAclEntitySegmentOrFail()),
            );

        // Act
        $aclEntityRuleCollectionTransfer = $this->tester->getFacade()->getAclEntityRuleCollection($aclEntityRuleCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclEntityRuleCollectionTransfer->getAclEntityRules());
        $this->assertSameAclEntityRuleTransfer($aclEntityRuleTransfer, $aclEntityRuleCollectionTransfer->getAclEntityRules()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testReturnsCorrectAclEntityRuleByIdAclRole(): void
    {
        // Arrange
        $aclEntityRuleTransfer = $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
            ],
        );

        $aclEntityRuleCriteriaTransfer = (new AclEntityRuleCriteriaTransfer())
            ->setAclEntityRuleCriteriaConditions(
                (new AclEntityRuleCriteriaConditionsTransfer())->addIdAclRole($aclEntityRuleTransfer->getIdAclRoleOrFail()),
            );
        // Act
        $aclEntityRuleCollectionTransfer = $this->tester->getFacade()->getAclEntityRuleCollection($aclEntityRuleCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclEntityRuleCollectionTransfer->getAclEntityRules());
        $this->assertSameAclEntityRuleTransfer($aclEntityRuleTransfer, $aclEntityRuleCollectionTransfer->getAclEntityRules()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testReturnsCorrectAclEntityRuleByEntity(): void
    {
        // Arrange
        $aclEntityRuleTransfer = $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
            ],
        );

        $aclEntityRuleCriteriaTransfer = (new AclEntityRuleCriteriaTransfer())
            ->setAclEntityRuleCriteriaConditions(
                (new AclEntityRuleCriteriaConditionsTransfer())->addEntity(static::ACL_ENTITY_SEGMENT_TARGET_ENTITY),
            );

        // Act
        $aclEntityRuleCollectionTransfer = $this->tester->getFacade()->getAclEntityRuleCollection($aclEntityRuleCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclEntityRuleCollectionTransfer->getAclEntityRules());
        $this->assertSameAclEntityRuleTransfer($aclEntityRuleTransfer, $aclEntityRuleCollectionTransfer->getAclEntityRules()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testReturnsCorrectAclEntityRuleByPermissionMask(): void
    {
        // Arrange
        $aclEntityRuleTransfer = $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
            ],
        );

        $aclEntityRuleCriteriaTransfer = (new AclEntityRuleCriteriaTransfer())
            ->setAclEntityRuleCriteriaConditions(
                (new AclEntityRuleCriteriaConditionsTransfer())->addPermissionMask($aclEntityRuleTransfer->getPermissionMaskOrFail()),
            );

        // Act
        $aclEntityRuleCollectionTransfer = $this->tester->getFacade()->getAclEntityRuleCollection($aclEntityRuleCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclEntityRuleCollectionTransfer->getAclEntityRules());
        $this->assertSameAclEntityRuleTransfer($aclEntityRuleTransfer, $aclEntityRuleCollectionTransfer->getAclEntityRules()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testReturnsCorrectAclEntityRuleByScope(): void
    {
        // Arrange
        $aclEntityRuleTransfer = $this->tester->haveAclEntityRule([
            AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
            AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
            AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
        ]);

        $aclEntityRuleCriteriaTransfer = (new AclEntityRuleCriteriaTransfer())
            ->setAclEntityRuleCriteriaConditions(
                (new AclEntityRuleCriteriaConditionsTransfer())->addScope(AclEntityConstants::SCOPE_GLOBAL),
            );

        // Act
        $aclEntityRuleCollectionTransfer = $this->tester->getFacade()->getAclEntityRuleCollection($aclEntityRuleCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclEntityRuleCollectionTransfer->getAclEntityRules());
        $this->assertSameAclEntityRuleTransfer($aclEntityRuleTransfer, $aclEntityRuleCollectionTransfer->getAclEntityRules()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testReturnsAclEntityRulesPaginatedByLimitAndOffset(): void
    {
        // Arrange
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
            ],
        );

        $aclEntityRuleTransfer2 = $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY_TWO,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
            ],
        );

        $paginationTransfer = (new PaginationTransfer())
            ->setLimit(1)
            ->setOffset(1);

        $aclEntityRuleCriteriaTransfer = (new AclEntityRuleCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $aclEntityRuleCollectionTransfer = $this->tester->getFacade()->getAclEntityRuleCollection($aclEntityRuleCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclEntityRuleCollectionTransfer->getAclEntityRules());
        $this->assertSameAclEntityRuleTransfer($aclEntityRuleTransfer2, $aclEntityRuleCollectionTransfer->getAclEntityRules()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testReturnsAclEntityRulesPaginatedByPageAndMaxPerPage(): void
    {
        // Arrange
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
            ],
        );

        $aclEntityRuleTransfer2 = $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY_TWO,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
            ],
        );

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(1);

        $aclEntityRuleCriteriaTransfer = (new AclEntityRuleCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $aclEntityRuleCollectionTransfer = $this->tester->getFacade()->getAclEntityRuleCollection($aclEntityRuleCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclEntityRuleCollectionTransfer->getAclEntityRules());
        $this->assertSameAclEntityRuleTransfer($aclEntityRuleTransfer2, $aclEntityRuleCollectionTransfer->getAclEntityRules()->getIterator()->current());

        $this->assertSame(2, $aclEntityRuleCollectionTransfer->getPaginationOrFail()->getNbResults());

        $paginationTransfer = $aclEntityRuleCollectionTransfer->getPaginationOrFail();

        $this->assertSame(2, $paginationTransfer->getPageOrFail());
        $this->assertSame(1, $paginationTransfer->getMaxPerPageOrFail());
        $this->assertSame(2, $paginationTransfer->getNbResultsOrFail());
        $this->assertSame(2, $paginationTransfer->getFirstIndexOrFail());
        $this->assertSame(2, $paginationTransfer->getLastIndexOrFail());
        $this->assertSame(1, $paginationTransfer->getFirstPage());
        $this->assertSame(2, $paginationTransfer->getLastPageOrFail());
        $this->assertSame(2, $paginationTransfer->getNextPageOrFail());
        $this->assertSame(1, $paginationTransfer->getPreviousPageOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsAclEntityRulesSortedByEntityFieldDesc(): void
    {
        // Arrange
        $aclEntityRuleTransfer1 = $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY_TWO,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
            ],
        );

        $sortTransfer = (new SortTransfer())
            ->setField(AclEntityRuleTransfer::ENTITY)
            ->setIsAscending(false);

        $aclEntityRuleCriteriaTransfer = (new AclEntityRuleCriteriaTransfer())
            ->addSort($sortTransfer);

        // Act
        $aclEntityRuleCollectionTransfer = $this->tester->getFacade()->getAclEntityRuleCollection($aclEntityRuleCriteriaTransfer);

        // Assert
        $this->assertCount(2, $aclEntityRuleCollectionTransfer->getAclEntityRules());
        $this->assertSameAclEntityRuleTransfer($aclEntityRuleTransfer1, $aclEntityRuleCollectionTransfer->getAclEntityRules()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testReturnsAclEntityRulesSortedByEntityFieldAsc(): void
    {
        // Arrange
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
            ],
        );

        $aclEntityRuleTransfer2 = $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY_TWO,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
            ],
        );

        $sortTransfer = (new SortTransfer())
            ->setField(AclEntityRuleTransfer::ENTITY)
            ->setIsAscending(true);

        $aclEntityRuleCriteriaTransfer = (new AclEntityRuleCriteriaTransfer())
            ->addSort($sortTransfer);

        // Act
        $aclEntityRuleCollectionTransfer = $this->tester->getFacade()->getAclEntityRuleCollection($aclEntityRuleCriteriaTransfer);

        // Assert
        $this->assertCount(2, $aclEntityRuleCollectionTransfer->getAclEntityRules());
        $this->assertSameAclEntityRuleTransfer($aclEntityRuleTransfer2, $aclEntityRuleCollectionTransfer->getAclEntityRules()->getIterator()->current());
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleTransfer $expectedAclEntityRuleTransfer
     * @param \Generated\Shared\Transfer\AclEntityRuleTransfer $aclEntityRuleTransfer
     *
     * @return void
     */
    protected function assertSameAclEntityRuleTransfer(
        AclEntityRuleTransfer $expectedAclEntityRuleTransfer,
        AclEntityRuleTransfer $aclEntityRuleTransfer
    ) {
        $this->assertSame($expectedAclEntityRuleTransfer->getIdAclEntityRule(), $aclEntityRuleTransfer->getIdAclEntityRule());
        $this->assertSame($expectedAclEntityRuleTransfer->getScope(), $aclEntityRuleTransfer->getScope());
        $this->assertSame($expectedAclEntityRuleTransfer->getEntity(), $aclEntityRuleTransfer->getEntity());
        $this->assertSame($expectedAclEntityRuleTransfer->getIdAclRole(), $aclEntityRuleTransfer->getIdAclRole());
        $this->assertSame($expectedAclEntityRuleTransfer->getPermissionMask(), $aclEntityRuleTransfer->getPermissionMask());
    }
}
