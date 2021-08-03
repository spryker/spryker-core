<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclEntity\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\AclEntitySegmentCriteriaTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclRoleCriteriaTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Propel\Runtime\Exception\ClassNotFoundException;
use Propel\Runtime\Exception\PropelException;
use Spryker\Shared\AclEntity\AclEntityConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclEntity
 * @group Business
 * @group Facade
 * @group AclEntityFacadeTest
 * Add your own group annotations below this line
 */
class AclEntityFacadeTest extends Unit
{
    protected const ACL_ROLE_TEST_NAME = 'role test';
    protected const ACL_ENTITY_RULE_ENTITY = 'Foo\Bar\Class';
    protected const TEST_MERCHANT_REFERENCE = 'test merchant segment reference';

    /**
     * @var \SprykerTest\Zed\AclEntity\AclEntityBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\RolesTransfer
     */
    protected $roleTransfer;

    /**
     * @var \Generated\Shared\Transfer\AclEntitySegmentTransfer
     */
    protected $aclEntitySegmentTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => static::ACL_ROLE_TEST_NAME]);
    }

    /**
     * @return void
     */
    protected function _tearDown(): void
    {
        parent::_tearDown();

        $this->tester->deleteRoles(
            (new AclRoleCriteriaTransfer())->setName(static::ACL_ROLE_TEST_NAME)
        );
        $this->tester->deleteAclEntityRules(
            (new AclEntityRuleCriteriaTransfer())->addIdAclRole($this->roleTransfer->getIdAclRole())
        );
        $this->tester->deleteAclEntitySegments(
            (new AclEntitySegmentCriteriaTransfer())->addReference(static::TEST_MERCHANT_REFERENCE)
        );
    }

    /**
     * @return void
     */
    public function testGetAclEntityMetadataConfigReturnsAclEntityMetadataConfigTransfer(): void
    {
        // Arrange
        $aclEntityFacade = $this->tester->getFacade();

        // Act
        $aclEntityMetadataConfigTransfer = $aclEntityFacade->getAclEntityMetadataConfig();

        // Assert
        $this->assertInstanceOf(AclEntityMetadataConfigTransfer::class, $aclEntityMetadataConfigTransfer);
    }

    /**
     * @return void
     */
    public function testExpandAclRolesExpandsAclRoleWithAclEntityRule(): void
    {
        // Arrange
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => static::ACL_ENTITY_RULE_ENTITY,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ]
        );
        $rolesTransfer = (new RolesTransfer())
            ->addRole($this->roleTransfer);

        // Act
        $rolesTransfer = $this->tester->getFacade()->expandAclRoles($rolesTransfer);

        // Assert
        $this->assertCount(1, $rolesTransfer->getRoles()->getIterator()->current()->getAclEntityRules());
    }

    /**
     * @return void
     */
    public function testSaveAclRulesSuccess(): void
    {
        // Arrange
        $aclEntityRuleTransfer = (new AclEntityRuleTransfer())
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setEntity(static::ACL_ENTITY_RULE_ENTITY)
            ->setIdAclRole($this->roleTransfer->getIdAclRoleOrFail())
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CREATE);

        $aclEntityRuleTransfer2 = (new AclEntityRuleTransfer())
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setEntity(static::ACL_ENTITY_RULE_ENTITY)
            ->setIdAclRole($this->roleTransfer->getIdAclRoleOrFail())
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers = new ArrayObject([$aclEntityRuleTransfer2, $aclEntityRuleTransfer]);

        // Act
        $this->tester->getFacade()->saveAclEntityRules($aclEntityRuleTransfers);

        // Assert
        $this->assertSame(2, $this->tester->getAclRulesCount($this->roleTransfer->getIdAclRoleOrFail()));
    }

    /**
     * @return void
     */
    public function testCreateAclEntitySegmentMerchantSuccess(): void
    {
        $reference = static::TEST_MERCHANT_REFERENCE;

        // Arrange
        $aclEntitySegmentRequestTransfer = (new AclEntitySegmentRequestTransfer())
            ->setName('Test')
            ->setEntity(SpyMerchant::class)
            ->setEntityIds([1, 2, 3])
            ->setReference($reference);

        // Act
        $aclEntitySegmentResponseTransfer = $this->tester->getFacade()->createAclEntitySegment($aclEntitySegmentRequestTransfer);
        $aclEntitySegment = $aclEntitySegmentResponseTransfer->getAclEntitySegmentOrFail();
        $aclEntitySegmentMerchants = $this->tester->findAclEntitySegmentMerchants($aclEntitySegment->getIdAclEntitySegment());

        // Assert
        $this->assertSame($reference, $aclEntitySegment->getReference());
        $this->assertSame('Test', $aclEntitySegment->getName());
        $this->assertCount(3, $aclEntitySegmentMerchants);
    }

    /**
     * @return void
     */
    public function testCreateAclEntitySegmentMerchantWrongMerchantIdException(): void
    {
        $reference = static::TEST_MERCHANT_REFERENCE;

        // Arrange
        $aclEntitySegmentRequestTransfer = (new AclEntitySegmentRequestTransfer())
            ->setName('Test')
            ->setEntity(SpyMerchant::class)
            ->setEntityIds([444])
            ->setReference($reference);

        $this->expectException(PropelException::class);

        // Act
        $this->tester->getFacade()->createAclEntitySegment($aclEntitySegmentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateAclEntitySegmentWrongSegmentClassException(): void
    {
        $reference = static::TEST_MERCHANT_REFERENCE;

        // Arrange
        $aclEntitySegmentRequestTransfer = (new AclEntitySegmentRequestTransfer())
            ->setName('Test')
            ->setEntity('Wrong Class')
            ->setEntityIds([1])
            ->setReference($reference);

        $this->expectException(ClassNotFoundException::class);

        // Act
        $this->tester->getFacade()->createAclEntitySegment($aclEntitySegmentRequestTransfer);
    }
}
