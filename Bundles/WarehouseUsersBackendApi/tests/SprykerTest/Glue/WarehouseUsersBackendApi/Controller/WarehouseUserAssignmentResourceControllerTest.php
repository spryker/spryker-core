<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\WarehouseUsersBackendApi\Controller;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\WarehouseUserAssignmentsBackendApiAttributesBuilder;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\WarehousesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use Spryker\Glue\WarehouseUsersBackendApi\Controller\WarehouseUserAssignmentsResourceController;
use SprykerTest\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group WarehouseUsersBackendApi
 * @group Controller
 * @group WarehouseUserAssignmentResourceControllerTest
 * Add your own group annotations below this line
 */
class WarehouseUserAssignmentResourceControllerTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_UUID = 'fake-uuid';

    /**
     * @var \SprykerTest\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiTester
     */
    protected WarehouseUsersBackendApiTester $tester;

    /**
     * @uses \Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig::RESPONSE_DETAILS_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND
     *
     * @var string
     */
    protected const ERROR_MESSAGE_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND = 'Warehouse user assignment not found.';

    /**
     * @uses \Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig::RESPONSE_DETAILS_OPERATION_IS_FORBIDDEN
     *
     * @var string
     */
    protected const ERROR_MESSAGE_WAREHOUSE_USER_ASSIGNMENT_OPERATION_FORBIDDEN = 'Operation is forbidden.';

    /**
     * @uses \Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig::RESPONSE_DETAILS_USER_NOT_FOUND
     *
     * @var string
     */
    protected const ERROR_MESSAGE_WAREHOUSE_USER_NOT_FOUND = 'User not found.';

    /**
     * @return void
     */
    public function testGetCollectionActionReturnsCollectionOfAllWarehouseUserAssignmentsForAdminUser(): void
    {
        // Arrange
        $this->tester->ensureWarehouseUserAssignmentTableIsEmpty();

        $adminUserTransfer = $this->tester->haveUser();
        $warehouseUserTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);

        $stockTransfer = $this->tester->haveStock();
        $adminUserWarehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $adminUserTransfer,
            $stockTransfer,
        );
        $warehouseUserWarehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $warehouseUserTransfer,
            $stockTransfer,
        );

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($adminUserTransfer->getIdUserOrFail()),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->getCollectionAction($glueRequestTransfer);

        // Assert
        $this->assertCount(0, $glueResponseTransfer->getErrors());
        $this->assertCount(2, $glueResponseTransfer->getResources());

        $adminUserWarehouseUserAssignmentGlueResourceTransfer = $this->tester->findGlueResourceByUuid(
            $glueResponseTransfer->getResources(),
            $adminUserWarehouseUserAssignmentTransfer->getUuidOrFail(),
        );
        $this->tester->assertNotNull($adminUserWarehouseUserAssignmentGlueResourceTransfer);
        $this->tester->assertWarehouseUserAssignmentsResource(
            $adminUserWarehouseUserAssignmentTransfer,
            $adminUserWarehouseUserAssignmentGlueResourceTransfer,
        );

        $warehouseUserUserWarehouseUserAssignmentGlueResourceTransfer = $this->tester->findGlueResourceByUuid(
            $glueResponseTransfer->getResources(),
            $warehouseUserWarehouseUserAssignmentTransfer->getUuidOrFail(),
        );
        $this->tester->assertNotNull($warehouseUserUserWarehouseUserAssignmentGlueResourceTransfer);
        $this->tester->assertWarehouseUserAssignmentsResource(
            $warehouseUserWarehouseUserAssignmentTransfer,
            $warehouseUserUserWarehouseUserAssignmentGlueResourceTransfer,
        );
    }

    /**
     * @return void
     */
    public function testGetCollectionActionReturnsCollectionOfUsersWarehouseUserAssignmentsForWarehouseUser(): void
    {
        // Arrange
        $this->tester->ensureWarehouseUserAssignmentTableIsEmpty();

        $userTransfer = $this->tester->haveUser();
        $warehouseUserTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);

        $stockTransfer = $this->tester->haveStock();
        $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );
        $warehouseUserWarehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $warehouseUserTransfer,
            $stockTransfer,
        );

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($warehouseUserTransfer->getIdUserOrFail()),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->getCollectionAction($glueRequestTransfer);

        // Assert
        $this->assertCount(0, $glueResponseTransfer->getErrors());
        $this->assertCount(1, $glueResponseTransfer->getResources());

        $this->tester->assertWarehouseUserAssignmentsResource(
            $warehouseUserWarehouseUserAssignmentTransfer,
            $glueResponseTransfer->getResources()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testGetCollectionActionReturnsForbiddenErrorWhenUserIsNotProvidedInRequest(): void
    {
        // Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(new GlueRequestUserTransfer());

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->getCollectionAction($glueRequestTransfer);

        // Assert
        $this->assertCount(1, $glueResponseTransfer->getErrors());
        $this->assertCount(0, $glueResponseTransfer->getResources());
        $this->assertSame(Response::HTTP_FORBIDDEN, $glueResponseTransfer->getHttpStatus());

        $glueErrorTransfer = $glueResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(Response::HTTP_FORBIDDEN, $glueErrorTransfer->getStatus());
        $this->assertSame(static::ERROR_MESSAGE_WAREHOUSE_USER_ASSIGNMENT_OPERATION_FORBIDDEN, $glueErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testGetCollectionActionReturnsForbiddenErrorWhenInvalidSurrogateUserIdentifierProvidedInRequest(): void
    {
        // Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier(-1),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->getCollectionAction($glueRequestTransfer);

        // Assert
        $this->assertCount(1, $glueResponseTransfer->getErrors());
        $this->assertCount(0, $glueResponseTransfer->getResources());
        $this->assertSame(Response::HTTP_FORBIDDEN, $glueResponseTransfer->getHttpStatus());

        $glueErrorTransfer = $glueResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(Response::HTTP_FORBIDDEN, $glueErrorTransfer->getStatus());
        $this->assertSame(static::ERROR_MESSAGE_WAREHOUSE_USER_ASSIGNMENT_OPERATION_FORBIDDEN, $glueErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testGetActionReturnsOwnWarehouseUserAssignmentsForAdminUser(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($userTransfer->getIdUserOrFail()),
        )->setResource(
            (new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->getAction($glueRequestTransfer);

        // Assert
        $this->assertCount(0, $glueResponseTransfer->getErrors());
        $this->assertCount(1, $glueResponseTransfer->getResources());

        $this->tester->assertWarehouseUserAssignmentsResource(
            $warehouseUserAssignmentTransfer,
            $glueResponseTransfer->getResources()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testGetActionReturnsAnotherUsersWarehouseUserAssignmentsForAdminUser(): void
    {
        // Arrange
        $adminUserTransfer = $this->tester->haveUser();
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($adminUserTransfer->getIdUserOrFail()),
        )->setResource(
            (new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->getAction($glueRequestTransfer);

        // Assert
        $this->assertCount(0, $glueResponseTransfer->getErrors());
        $this->assertCount(1, $glueResponseTransfer->getResources());

        $this->tester->assertWarehouseUserAssignmentsResource(
            $warehouseUserAssignmentTransfer,
            $glueResponseTransfer->getResources()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testGetActionReturnsNotFoundErrorForNonExistentWarehouseUserAssignmentsUuid(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $warehouseUserTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer = $this->tester->haveStock();
        $this->tester->haveWarehouseUserAssignment(
            $warehouseUserTransfer,
            $stockTransfer,
        );

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($userTransfer->getIdUserOrFail()),
        )->setResource(
            (new GlueResourceTransfer())->setId(static::FAKE_UUID),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->getAction($glueRequestTransfer);

        // Assert
        $this->assertCount(1, $glueResponseTransfer->getErrors());
        $this->assertCount(0, $glueResponseTransfer->getResources());
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueResponseTransfer->getHttpStatus());

        $glueErrorTransfer = $glueResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueErrorTransfer->getStatus());
        $this->assertSame(static::ERROR_MESSAGE_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND, $glueErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testGetActionReturnsOwnWarehouseUserAssignmentsForWarehouseUser(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($userTransfer->getIdUserOrFail()),
        )->setResource(
            (new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->getAction($glueRequestTransfer);

        // Assert
        $this->assertCount(0, $glueResponseTransfer->getErrors());
        $this->assertCount(1, $glueResponseTransfer->getResources());

        $this->tester->assertWarehouseUserAssignmentsResource(
            $warehouseUserAssignmentTransfer,
            $glueResponseTransfer->getResources()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testGetActionReturnsNotFoundErrorWhenAnotherUsersWarehouseUserAssignmentsUuidProvidedForWarehouseUser(): void
    {
        // Arrange
        $warehouseUserTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);

        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($warehouseUserTransfer->getIdUserOrFail()),
        )->setResource(
            (new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->getAction($glueRequestTransfer);

        // Assert
        $this->assertCount(1, $glueResponseTransfer->getErrors());
        $this->assertCount(0, $glueResponseTransfer->getResources());
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueResponseTransfer->getHttpStatus());

        $glueErrorTransfer = $glueResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueErrorTransfer->getStatus());
        $this->assertSame(static::ERROR_MESSAGE_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND, $glueErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testGetActionReturnsNotFoundErrorWhenUsersIsNotProvidedInRequest(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setRequestUser(new GlueRequestUserTransfer())
            ->setResource(
                (new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()),
            );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->getAction($glueRequestTransfer);

        // Assert
        $this->assertCount(1, $glueResponseTransfer->getErrors());
        $this->assertCount(0, $glueResponseTransfer->getResources());
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueResponseTransfer->getHttpStatus());

        $glueErrorTransfer = $glueResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueErrorTransfer->getStatus());
        $this->assertSame(static::ERROR_MESSAGE_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND, $glueErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testGetActionReturnsNotFoundErrorWhenUsersInvalidSurrogateUserIdentifierProvidedInRequest(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setRequestUser(
                (new GlueRequestUserTransfer())->setSurrogateIdentifier(-1),
            )
            ->setResource(
                (new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()),
            );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->getAction($glueRequestTransfer);

        // Assert
        $this->assertCount(1, $glueResponseTransfer->getErrors());
        $this->assertCount(0, $glueResponseTransfer->getResources());
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueResponseTransfer->getHttpStatus());

        $glueErrorTransfer = $glueResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueErrorTransfer->getStatus());
        $this->assertSame(static::ERROR_MESSAGE_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND, $glueErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testPostActionReturnsPersistedWarehouseUserAssignmentsResource(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentsRestResourceAttributesTransfer = (new WarehouseUserAssignmentsBackendApiAttributesBuilder([
            WarehouseUserAssignmentsBackendApiAttributesTransfer::USER_UUID => $userTransfer->getUuidOrFail(),
            WarehouseUserAssignmentsBackendApiAttributesTransfer::IS_ACTIVE => true,
        ]))->withWarehouse([
            WarehousesBackendApiAttributesTransfer::UUID => $stockTransfer->getUuidOrFail(),
        ])->build();

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($userTransfer->getIdUserOrFail()),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->postAction(
            $warehouseUserAssignmentsRestResourceAttributesTransfer,
            $glueRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $glueResponseTransfer->getErrors());
        $this->assertCount(1, $glueResponseTransfer->getResources());

        $glueResourceTransfer = $glueResponseTransfer->getResources()->getIterator()->current();
        $this->assertNotNull($glueResourceTransfer->getId());
        $this->assertInstanceOf(WarehouseUserAssignmentsBackendApiAttributesTransfer::class, $glueResourceTransfer->getAttributes());
        $this->assertSame($userTransfer->getUuidOrFail(), $glueResourceTransfer->getAttributes()->getUserUuid());
        $this->assertNotNull($glueResourceTransfer->getAttributes()->getWarehouse());
        $this->assertSame($stockTransfer->getUuid(), $glueResourceTransfer->getAttributes()->getWarehouse()->getUuid());
    }

    /**
     * @return void
     */
    public function testPostActionReturnsNotFoundErrorWhenWarehouseUserCreatesWarehouseUserAssignmentForAnotherUser(): void
    {
        // Arrange
        $requestUserTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $warehouseUserTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);

        $warehouseUserAssignmentsRestResourceAttributesTransfer = (new WarehouseUserAssignmentsBackendApiAttributesBuilder([
            WarehouseUserAssignmentsBackendApiAttributesTransfer::USER_UUID => $warehouseUserTransfer->getUuidOrFail(),
            WarehouseUserAssignmentsBackendApiAttributesTransfer::IS_ACTIVE => true,
        ]))->withWarehouse([
            WarehousesBackendApiAttributesTransfer::UUID => $this->tester->haveStock()->getUuidOrFail(),
        ])->build();

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($requestUserTransfer->getIdUserOrFail()),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->postAction(
            $warehouseUserAssignmentsRestResourceAttributesTransfer,
            $glueRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $glueResponseTransfer->getErrors());
        $this->assertCount(0, $glueResponseTransfer->getResources());
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueResponseTransfer->getHttpStatus());

        $glueErrorTransfer = $glueResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueErrorTransfer->getStatus());
        $this->assertSame(static::ERROR_MESSAGE_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND, $glueErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testPostActionReturnsPersistedWarehouseUserAssignmentWhenAdminUserCreatesWarehouseUserAssignmentForAnotherUser(): void
    {
        // Arrange
        $requestUserTransfer = $this->tester->haveUser();
        $warehouseUserTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentsRestResourceAttributesTransfer = (new WarehouseUserAssignmentsBackendApiAttributesBuilder([
            WarehouseUserAssignmentsBackendApiAttributesTransfer::USER_UUID => $warehouseUserTransfer->getUuidOrFail(),
            WarehouseUserAssignmentsBackendApiAttributesTransfer::IS_ACTIVE => true,
        ]))->withWarehouse([
            WarehousesBackendApiAttributesTransfer::UUID => $stockTransfer->getUuidOrFail(),
        ])->build();

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($requestUserTransfer->getIdUserOrFail()),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->postAction(
            $warehouseUserAssignmentsRestResourceAttributesTransfer,
            $glueRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $glueResponseTransfer->getErrors());
        $this->assertCount(1, $glueResponseTransfer->getResources());

        $glueResourceTransfer = $glueResponseTransfer->getResources()->getIterator()->current();
        $this->assertNotNull($glueResourceTransfer->getId());
        $this->assertInstanceOf(WarehouseUserAssignmentsBackendApiAttributesTransfer::class, $glueResourceTransfer->getAttributes());
        $this->assertSame($warehouseUserTransfer->getUuidOrFail(), $glueResourceTransfer->getAttributes()->getUserUuid());
        $this->assertNotNull($glueResourceTransfer->getAttributes()->getWarehouse());
        $this->assertSame($stockTransfer->getUuid(), $glueResourceTransfer->getAttributes()->getWarehouse()->getUuid());
    }

    /**
     * @return void
     */
    public function testPostActionReturnsUserNotFoundErrorWhenAdminUserCreatesWarehouseUserAssignmentForNonWarehouseUser(): void
    {
        // Arrange
        $adminUserTransfer = $this->tester->haveUser();
        $nonWarehouseUserTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => false]);

        $warehouseUserAssignmentsRestResourceAttributesTransfer = (new WarehouseUserAssignmentsBackendApiAttributesBuilder([
            WarehouseUserAssignmentsBackendApiAttributesTransfer::USER_UUID => $nonWarehouseUserTransfer->getUuidOrFail(),
            WarehouseUserAssignmentsBackendApiAttributesTransfer::IS_ACTIVE => true,
        ]))->withWarehouse([
            WarehousesBackendApiAttributesTransfer::UUID => $this->tester->haveStock()->getUuidOrFail(),
        ])->build();

        $glueRequestUserTransfer = (new GlueRequestUserTransfer())->setSurrogateIdentifier($adminUserTransfer->getIdUserOrFail());
        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser($glueRequestUserTransfer);

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->postAction(
            $warehouseUserAssignmentsRestResourceAttributesTransfer,
            $glueRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $glueResponseTransfer->getErrors());
        $this->assertCount(0, $glueResponseTransfer->getResources());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $glueResponseTransfer->getHttpStatus());

        $glueErrorTransfer = $glueResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $glueErrorTransfer->getStatus());
        $this->assertSame(static::ERROR_MESSAGE_WAREHOUSE_USER_NOT_FOUND, $glueErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testPatchActionReturnsPersistedWarehouseUserAssignmentsResource(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => false],
        );
        $warehouseUserAssignmentsRestResourceAttributesTransfer = (new WarehouseUserAssignmentsBackendApiAttributesBuilder([
            WarehouseUserAssignmentsBackendApiAttributesTransfer::IS_ACTIVE => true,
        ]))->build();

        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource((new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()))
            ->setRequestUser((new GlueRequestUserTransfer())->setSurrogateIdentifier($userTransfer->getIdUserOrFail()));

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->patchAction(
            $warehouseUserAssignmentsRestResourceAttributesTransfer,
            $glueRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $glueResponseTransfer->getErrors());
        $this->assertCount(1, $glueResponseTransfer->getResources());

        $glueResourceTransfer = $glueResponseTransfer->getResources()->getIterator()->current();
        $this->assertNotNull($glueResourceTransfer->getId());
        $this->assertInstanceOf(WarehouseUserAssignmentsBackendApiAttributesTransfer::class, $glueResourceTransfer->getAttributes());
        $this->assertTrue($glueResourceTransfer->getAttributes()->getIsActive());
        $this->assertSame($userTransfer->getUuidOrFail(), $glueResourceTransfer->getAttributes()->getUserUuid());
        $this->assertNotNull($glueResourceTransfer->getAttributes()->getWarehouse());
        $this->assertSame($stockTransfer->getUuid(), $glueResourceTransfer->getAttributes()->getWarehouse()->getUuid());
    }

    /**
     * @return void
     */
    public function testPatchActionReturnsPersistedWarehouseUserAssignmentsResourceWhenAllPossibleDataProvided(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => false],
        );
        $warehouseUserAssignmentsRestResourceAttributesTransfer = (new WarehouseUserAssignmentsBackendApiAttributesBuilder([
            WarehouseUserAssignmentsBackendApiAttributesTransfer::IS_ACTIVE => true,
            WarehouseUserAssignmentsBackendApiAttributesTransfer::USER_UUID => $userTransfer->getUuidOrFail(),
            WarehouseUserAssignmentsBackendApiAttributesTransfer::WAREHOUSE => [
                WarehousesBackendApiAttributesTransfer::UUID => $stockTransfer->getUuidOrFail(),
            ],
        ]))->build();

        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource((new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()))
            ->setRequestUser((new GlueRequestUserTransfer())->setSurrogateIdentifier($userTransfer->getIdUserOrFail()));

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->patchAction(
            $warehouseUserAssignmentsRestResourceAttributesTransfer,
            $glueRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $glueResponseTransfer->getErrors());
        $this->assertCount(1, $glueResponseTransfer->getResources());

        $glueResourceTransfer = $glueResponseTransfer->getResources()->getIterator()->current();
        $this->assertNotNull($glueResourceTransfer->getId());
        $this->assertInstanceOf(WarehouseUserAssignmentsBackendApiAttributesTransfer::class, $glueResourceTransfer->getAttributes());
        $this->assertTrue($glueResourceTransfer->getAttributes()->getIsActive());
        $this->assertSame($userTransfer->getUuidOrFail(), $glueResourceTransfer->getAttributes()->getUserUuid());
        $this->assertNotNull($glueResourceTransfer->getAttributes()->getWarehouse());
        $this->assertSame($stockTransfer->getUuid(), $glueResourceTransfer->getAttributes()->getWarehouse()->getUuid());
    }

    /**
     * @return void
     */
    public function testPatchActionReturnsNotFoundErrorWhenWarehouseUserUpdatesWarehouseUserAssignmentOfAnotherUser(): void
    {
        // Arrange
        $warehouseUserTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => false],
        );
        $warehouseUserAssignmentsRestResourceAttributesTransfer = (new WarehouseUserAssignmentsBackendApiAttributesBuilder([
            WarehouseUserAssignmentsBackendApiAttributesTransfer::IS_ACTIVE => true,
        ]))->build();

        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource((new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()))
            ->setRequestUser((new GlueRequestUserTransfer())->setSurrogateIdentifier($warehouseUserTransfer->getIdUserOrFail()));

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->patchAction(
            $warehouseUserAssignmentsRestResourceAttributesTransfer,
            $glueRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $glueResponseTransfer->getErrors());
        $this->assertCount(0, $glueResponseTransfer->getResources());
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueResponseTransfer->getHttpStatus());

        $glueErrorTransfer = $glueResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueErrorTransfer->getStatus());
        $this->assertSame(static::ERROR_MESSAGE_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND, $glueErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testPatchActionReturnsPersistedWarehouseUserAssignmentWhenAdminUserUpdatesWarehouseUserAssignmentOfAnotherUser(): void
    {
        // Arrange
        $adminUserTransfer = $this->tester->haveUser();
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => false],
        );
        $warehouseUserAssignmentsRestResourceAttributesTransfer = (new WarehouseUserAssignmentsBackendApiAttributesBuilder([
            WarehouseUserAssignmentsBackendApiAttributesTransfer::IS_ACTIVE => true,
        ]))->build();

        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource((new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()))
            ->setRequestUser((new GlueRequestUserTransfer())->setSurrogateIdentifier($adminUserTransfer->getIdUserOrFail()));

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->patchAction(
            $warehouseUserAssignmentsRestResourceAttributesTransfer,
            $glueRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $glueResponseTransfer->getErrors());
        $this->assertCount(1, $glueResponseTransfer->getResources());

        $glueResourceTransfer = $glueResponseTransfer->getResources()->getIterator()->current();
        $this->assertNotNull($glueResourceTransfer->getId());
        $this->assertInstanceOf(WarehouseUserAssignmentsBackendApiAttributesTransfer::class, $glueResourceTransfer->getAttributes());
        $this->assertTrue($glueResourceTransfer->getAttributes()->getIsActive());
        $this->assertSame($userTransfer->getUuidOrFail(), $glueResourceTransfer->getAttributes()->getUserUuid());
        $this->assertNotNull($glueResourceTransfer->getAttributes()->getWarehouse());
        $this->assertSame($stockTransfer->getUuid(), $glueResourceTransfer->getAttributes()->getWarehouse()->getUuid());
    }

    /**
     * @return void
     */
    public function testDeleteActionDeletesWarehouseUserAssignmentFromDatabase(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($userTransfer->getIdUserOrFail()),
        )->setResource(
            (new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->deleteAction($glueRequestTransfer);

        // Arrange
        $this->assertSame(Response::HTTP_NO_CONTENT, $glueResponseTransfer->getHttpStatus());
        $this->tester->assertWarehouseUserAssignmentNotPersisted($warehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail());
    }

    /**
     * @return void
     */
    public function testDeleteActionDeletesWarehouseUserAssignmentFromDatabaseWhenAdminUserDeletesAnotherUsersWarehouseUserAssignment(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();

        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]),
            $this->tester->haveStock(),
        );

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($userTransfer->getIdUserOrFail()),
        )->setResource(
            (new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->deleteAction($glueRequestTransfer);

        // Arrange
        $this->assertSame(Response::HTTP_NO_CONTENT, $glueResponseTransfer->getHttpStatus());
        $this->tester->assertWarehouseUserAssignmentNotPersisted($warehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail());
    }

    /**
     * @return void
     */
    public function testDeleteActionDoesNotDeleteWarehouseUserAssignmentWhenWarehouseUserDeletesAnotherUsersWarehouseUserAssignment(): void
    {
        // Arrange
        $warehouseUserTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $requestUserTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);

        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $warehouseUserTransfer,
            $this->tester->haveStock(),
        );

        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(
            (new GlueRequestUserTransfer())->setSurrogateIdentifier($requestUserTransfer->getIdUserOrFail()),
        )->setResource(
            (new GlueResourceTransfer())->setId($warehouseUserAssignmentTransfer->getUuidOrFail()),
        );

        // Act
        $glueResponseTransfer = (new WarehouseUserAssignmentsResourceController())->deleteAction($glueRequestTransfer);

        // Arrange
        $this->assertSame(Response::HTTP_NOT_FOUND, $glueResponseTransfer->getHttpStatus());
        $this->tester->assertWarehouseUserAssignmentPersisted($warehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail());
    }
}
