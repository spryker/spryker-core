<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclEntity\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\AclEntityDependencyProvider;
use Spryker\Zed\AclEntity\Business\Exception\AclEntityMetadataConfigInvalidKeyException;
use Spryker\Zed\AclEntity\Business\Exception\AclEntityMetadataConfigParentEntityNotFoundException;
use Spryker\Zed\AclEntity\Business\Exception\AclEntityRuleReferencedEntityNotFoundException;
use Spryker\Zed\AclEntity\Business\Exception\DuplicatedAclEntityRuleException;
use Spryker\Zed\AclEntity\Business\Exception\InheritedScopeCanNotBeAssignedException;
use Spryker\Zed\AclEntity\Business\Exception\ReferencedSegmentConnectorEntityNotFoundException;
use Spryker\Zed\AclEntity\Business\Exception\SegmentConnectorEntityNotFoundException;
use SprykerTest\Zed\AclEntity\Plugin\AclEntityMetadataConfigExpanderPluginMock;
use SprykerTest\Zed\AclEntity\Plugin\AclEntityMetadataConfigWithInvalidKeyExpanderPluginMock;
use SprykerTest\Zed\AclEntity\Plugin\AclEntityMetadataConfigWithWrongParentEntityExpanderPluginMock;

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
    /**
     * @var string
     */
    protected const ACL_ROLE_TEST_NAME = 'role test';

    /**
     * @var string
     */
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

        $this->tester->setDependency(AclEntityDependencyProvider::PLUGINS_ACL_ENTITY_METADATA_COLLECTION_EXPANDER, [
            new AclEntityMetadataConfigExpanderPluginMock(),
        ]);

        $this->roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => static::ACL_ROLE_TEST_NAME]);
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
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ],
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
            ->setEntity(SpyMerchant::class)
            ->setIdAclRole($this->roleTransfer->getIdAclRoleOrFail())
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CREATE);

        $aclEntityRuleTransfer2 = (new AclEntityRuleTransfer())
            ->setScope(AclEntityConstants::SCOPE_SEGMENT)
            ->setEntity(SpyMerchant::class)
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
    public function testSaveAclRulesThrowsAclEntityRuleReferencedEntityNotFoundException(): void
    {
        // Arrange
        $newAclEntityRuleTransfer = (new AclEntityRuleTransfer())
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setEntity('test')
            ->setIdAclRole($this->roleTransfer->getIdAclRoleOrFail())
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CREATE);

        $aclEntityRuleTransfers = new ArrayObject([$newAclEntityRuleTransfer]);

        // Assert
        $this->expectException(AclEntityRuleReferencedEntityNotFoundException::class);

        // Act
        $this->tester->getFacade()->saveAclEntityRules($aclEntityRuleTransfers);
    }

    /**
     * @return void
     */
    public function testSaveAclRulesThrowsInheritedScopeCanNotBeAssignedException(): void
    {
        // Arrange
        $newAclEntityRuleTransfer = (new AclEntityRuleTransfer())
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setEntity(SpyMerchant::class)
            ->setIdAclRole($this->roleTransfer->getIdAclRoleOrFail())
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CREATE);

        $aclEntityRuleTransfers = new ArrayObject([$newAclEntityRuleTransfer]);

        // Assert
        $this->expectException(InheritedScopeCanNotBeAssignedException::class);

        // Act
        $this->tester->getFacade()->saveAclEntityRules($aclEntityRuleTransfers);
    }

    /**
     * @return void
     */
    public function testSaveAclRulesThrowsDuplicatedAclEntityRuleException(): void
    {
        // Arrange
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $this->roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ],
        );

        $newAclEntityRuleTransfer = (new AclEntityRuleTransfer())
            ->setScope(AclEntityConstants::SCOPE_SEGMENT)
            ->setEntity(SpyMerchant::class)
            ->setIdAclRole($this->roleTransfer->getIdAclRoleOrFail())
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CREATE);

        $aclEntityRuleTransfers = new ArrayObject([$newAclEntityRuleTransfer]);

        // Assert
        $this->expectException(DuplicatedAclEntityRuleException::class);

        // Act
        $this->tester->getFacade()->saveAclEntityRules($aclEntityRuleTransfers);
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
            ->setEntityIds([-199])
            ->setReference($reference);

        $this->expectException(Exception::class);

        // Act
        $this->tester->getFacade()->createAclEntitySegment($aclEntitySegmentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateAclEntitySegmentThrowsAclEntityReferencedSegmentConnectorEntityException(): void
    {
        $reference = static::TEST_MERCHANT_REFERENCE;

        // Arrange
        $aclEntitySegmentRequestTransfer = (new AclEntitySegmentRequestTransfer())
            ->setName('Test')
            ->setEntity('Wrong Class')
            ->setEntityIds([1])
            ->setReference($reference);

        $this->expectException(ReferencedSegmentConnectorEntityNotFoundException::class);

        // Act
        $this->tester->getFacade()->createAclEntitySegment($aclEntitySegmentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateAclEntitySegmentThrowsAclEntitySegmentConnectorEntityNotFoundException(): void
    {
        $reference = static::TEST_MERCHANT_REFERENCE;

        // Arrange
        $aclEntitySegmentRequestTransfer = (new AclEntitySegmentRequestTransfer())
            ->setName('Test')
            ->setEntity(SpyLocale::class)
            ->setEntityIds([1])
            ->setReference($reference);

        $this->expectException(SegmentConnectorEntityNotFoundException::class);

        // Act
        $this->tester->getFacade()->createAclEntitySegment($aclEntitySegmentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGetAclEntityMetadataConfigThrowsAclEntityMetadataConfigInvalidKeyException(): void
    {
        // Arrange
        $aclEntityFacade = $this->tester->getFacade();
        $this->tester->setDependency(AclEntityDependencyProvider::PLUGINS_ACL_ENTITY_METADATA_COLLECTION_EXPANDER, [
            new AclEntityMetadataConfigWithInvalidKeyExpanderPluginMock(),
        ]);

        $this->expectException(AclEntityMetadataConfigInvalidKeyException::class);

        // Act
        $aclEntityFacade->getAclEntityMetadataConfig();
    }

    /**
     * @return void
     */
    public function testGetAclEntityMetadataConfigThrowsAclEntityMetadataConfigParentEntityNotFoundException(): void
    {
        // Arrange
        $aclEntityFacade = $this->tester->getFacade();
        $this->tester->setDependency(AclEntityDependencyProvider::PLUGINS_ACL_ENTITY_METADATA_COLLECTION_EXPANDER, [
            new AclEntityMetadataConfigWithWrongParentEntityExpanderPluginMock(),
        ]);

        $this->expectException(AclEntityMetadataConfigParentEntityNotFoundException::class);

        // Act
        $aclEntityFacade->getAclEntityMetadataConfig();
    }
}
