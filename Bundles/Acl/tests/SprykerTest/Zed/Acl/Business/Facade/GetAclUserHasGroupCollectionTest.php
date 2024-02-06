<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclUserHasGroupConditionsTransfer;
use Generated\Shared\Transfer\AclUserHasGroupCriteriaTransfer;
use SprykerTest\Zed\Acl\AclBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Acl
 * @group Business
 * @group Facade
 * @group GetAclUserHasGroupCollectionTest
 * Add your own group annotations below this line
 */
class GetAclUserHasGroupCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Acl\AclBusinessTester
     */
    protected AclBusinessTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureAclUserHasGroupTableIsEmpty();
    }

    /**
     * @dataProvider getReturnsAllEntitiesDataProvider
     *
     * @param \Generated\Shared\Transfer\AclUserHasGroupCriteriaTransfer $aclUserHasGroupCriteriaTransfer
     *
     * @return void
     */
    public function testReturnsAllEntities(AclUserHasGroupCriteriaTransfer $aclUserHasGroupCriteriaTransfer): void
    {
        // Arrange
        $idUser1 = $this->tester->haveUser()->getIdUserOrFail();
        $idUser2 = $this->tester->haveUser()->getIdUserOrFail();
        $idAclGroup1 = $this->tester->haveGroup()->getIdAclGroupOrFail();
        $idAclGroup2 = $this->tester->haveGroup()->getIdAclGroupOrFail();
        $this->tester->haveAclUserHasGroup($idUser1, $idAclGroup1);
        $this->tester->haveAclUserHasGroup($idUser2, $idAclGroup2);
        $expectedUserIds = [$idUser1, $idUser2];

        // Act
        $aclUserHasGroupCollectionTransfer = $this->tester
            ->getFacade()
            ->getAclUserHasGroupCollection($aclUserHasGroupCriteriaTransfer);

        // Assert
        $this->assertCount(2, $aclUserHasGroupCollectionTransfer->getAclUserHasGroups());
        $this->assertContains(
            $aclUserHasGroupCollectionTransfer->getAclUserHasGroups()->offsetGet(0)->getUserOrFail()->getIdUser(),
            $expectedUserIds,
        );
        $this->assertContains(
            $aclUserHasGroupCollectionTransfer->getAclUserHasGroups()->offsetGet(1)->getUserOrFail()->getIdUser(),
            $expectedUserIds,
        );
    }

    /**
     * @return void
     */
    public function testFiltersByUserIds(): void
    {
        // Arrange
        $idUser1 = $this->tester->haveUser()->getIdUserOrFail();
        $idUser2 = $this->tester->haveUser()->getIdUserOrFail();
        $idAclGroup = $this->tester->haveGroup()->getIdAclGroupOrFail();
        $this->tester->haveAclUserHasGroup($idUser1, $idAclGroup);
        $this->tester->haveAclUserHasGroup($idUser2, $idAclGroup);

        $aclUserHasGroupCriteriaTransfer = (new AclUserHasGroupCriteriaTransfer())
            ->setAclUserHasGroupConditions((new AclUserHasGroupConditionsTransfer())->addIdUser($idUser1));

        // Act
        $aclUserHasGroupCollectionTransfer = $this->tester
            ->getFacade()
            ->getAclUserHasGroupCollection($aclUserHasGroupCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclUserHasGroupCollectionTransfer->getAclUserHasGroups());
        $this->assertSame(
            $idUser1,
            $aclUserHasGroupCollectionTransfer->getAclUserHasGroups()->offsetGet(0)->getUserOrFail()->getIdUser(),
        );
    }

    /**
     * @return void
     */
    public function testFiltersByAclGroupName(): void
    {
        // Arrange
        $idUser = $this->tester->haveUser()->getIdUserOrFail();
        $groupTransfer1 = $this->tester->haveGroup();
        $groupTransfer2 = $this->tester->haveGroup();
        $this->tester->haveAclUserHasGroup($idUser, $groupTransfer1->getIdAclGroupOrFail());
        $this->tester->haveAclUserHasGroup($idUser, $groupTransfer2->getIdAclGroupOrFail());

        $aclUserHasGroupCriteriaTransfer = (new AclUserHasGroupCriteriaTransfer())
            ->setAclUserHasGroupConditions((new AclUserHasGroupConditionsTransfer())->addGroupName($groupTransfer1->getNameOrFail()));

        // Act
        $aclUserHasGroupCollectionTransfer = $this->tester
            ->getFacade()
            ->getAclUserHasGroupCollection($aclUserHasGroupCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclUserHasGroupCollectionTransfer->getAclUserHasGroups());
        $this->assertSame(
            $groupTransfer1->getIdAclGroupOrFail(),
            $aclUserHasGroupCollectionTransfer->getAclUserHasGroups()->offsetGet(0)->getGroupOrFail()->getIdAclGroupOrFail(),
        );
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\AclUserHasGroupCriteriaTransfer>>
     */
    protected function getReturnsAllEntitiesDataProvider(): array
    {
        return [
            'When conditions transfer is not set' => [
                new AclUserHasGroupCriteriaTransfer(),
            ],
            'When conditions transfer is set without properties' => [
                (new AclUserHasGroupCriteriaTransfer())->setAclUserHasGroupConditions(
                    new AclUserHasGroupConditionsTransfer(),
                ),
            ],
        ];
    }
}
