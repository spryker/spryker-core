<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\WarehouseUserGui\Communication\WarehouseUserGuiCommunicationFactory getFactory()
 */
class AssignWarehouseController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_USER_UUID = 'user-uuid';

    /**
     * @var string
     */
    protected const URL_ASSIGN_WAREHOUSE = '/warehouse-user-gui/assign-warehouse';

    /**
     * @var string
     */
    protected const URL_ASSIGN_WAREHOUSE_TEMPLATE = self::URL_ASSIGN_WAREHOUSE . '?%s=%s';

    /**
     * @uses \Spryker\Zed\User\Communication\Controller\IndexController::indexAction()
     *
     * @var string
     */
    protected const URL_USER = '/user';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_USER_COULD_NOT_BE_FOUND = 'User could not be found.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_USER_UUID_IS_MISSING = 'User uuid is missing.';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_WAREHOUSE_USER_ASSIGNMENTS_UPDATED = 'Warehouse user assignments are updated.';

    /**
     * @uses \Spryker\Zed\WarehouseUserGui\Communication\Form\WarehouseUserForm::FIELD_USER_UUID
     *
     * @var string
     */
    protected const WAREHOUSE_USER_FORM_FIELD_USER_UUID = 'userUuid';

    /**
     * @uses \Spryker\Zed\WarehouseUserGui\Communication\Form\WarehouseUserForm::FIELD_UUIDS_WAREHOUSES_TO_ASSIGN
     *
     * @var string
     */
    protected const WAREHOUSE_USER_FORM_FIELD_UUIDS_WAREHOUSES_TO_ASSIGN = 'uuidsWarehousesToAssign';

    /**
     * @uses \Spryker\Zed\WarehouseUserGui\Communication\Form\WarehouseUserForm::FIELD_UUIDS_WAREHOUSES_TO_DEASSIGN
     *
     * @var string
     */
    protected const WAREHOUSE_USER_FORM_FIELD_UUIDS_WAREHOUSES_TO_DEASSIGN = 'uuidsWarehousesToDeassign';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        /** @var string $userUuid */
        $userUuid = $request->query->get(static::PARAM_USER_UUID);
        if (!$userUuid) {
            $this->addErrorMessage(static::ERROR_MESSAGE_USER_UUID_IS_MISSING);

            return $this->redirectResponse(static::URL_USER);
        }

        $userTransfer = $this->findUser($userUuid);

        if (!$userTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_USER_COULD_NOT_BE_FOUND);

            return $this->redirectResponse(static::URL_USER);
        }

        $warehouseUserForm = $this->getFactory()->createWarehouseUserForm(
            $this->getFactory()->createWarehouseUserFormDataProvider()->getData($userUuid),
        )->handleRequest($request);

        if ($warehouseUserForm->isSubmitted() && $warehouseUserForm->isValid()) {
            $this->manageWarehouseUserAssignments($warehouseUserForm->getData());
            $this->addSuccessMessage(static::SUCCESS_MESSAGE_WAREHOUSE_USER_ASSIGNMENTS_UPDATED);

            return $this->redirectResponse(sprintf(static::URL_ASSIGN_WAREHOUSE_TEMPLATE, static::PARAM_USER_UUID, $userUuid));
        }

        return $this->viewResponse([
            'availableWarehouses' => $this->getFactory()->createAvailableWarehouseTable($userTransfer)->render(),
            'assignedWarehouses' => $this->getFactory()->createAssignedWarehouseTable($userTransfer)->render(),
            'userTransfer' => $userTransfer,
            'usersUrl' => static::URL_USER,
            'form' => $warehouseUserForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableWarehouseTableAction(Request $request): JsonResponse
    {
        /** @var string|null $userUuid */
        $userUuid = $request->query->get(static::PARAM_USER_UUID);

        if (!$userUuid) {
            return $this->jsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        return $this->jsonResponse(
            $this->getFactory()->createAvailableWarehouseTable((new UserTransfer())->setUuid($userUuid))->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedWarehouseTableAction(Request $request): JsonResponse
    {
        /** @var string $userUuid */
        $userUuid = $request->query->get(static::PARAM_USER_UUID);

        if (!$userUuid) {
            return $this->jsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        return $this->jsonResponse(
            $this->getFactory()->createAssignedWarehouseTable((new UserTransfer())->setUuid($userUuid))->fetchData(),
        );
    }

    /**
     * @param array<string, mixed> $warehouseUserFormData
     *
     * @return void
     */
    protected function manageWarehouseUserAssignments(array $warehouseUserFormData): void
    {
        if ($warehouseUserFormData[static::WAREHOUSE_USER_FORM_FIELD_UUIDS_WAREHOUSES_TO_ASSIGN]) {
            $this->getFactory()
                ->getWarehouseUserFacade()
                ->createWarehouseUserAssignmentCollection(
                    $this->createWarehouseUserAssignmentCollectionRequestTransfer(
                        $warehouseUserFormData,
                    ),
                );
        }

        if ($warehouseUserFormData[static::WAREHOUSE_USER_FORM_FIELD_UUIDS_WAREHOUSES_TO_DEASSIGN]) {
            $this->getFactory()
                ->getWarehouseUserFacade()
                ->deleteWarehouseUserAssignmentCollection(
                    $this->createWarehouseUserAssignmentCollectionDeleteCriteriaTransfer($warehouseUserFormData),
                );
        }
    }

    /**
     * @param string $userUuid
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUser(string $userUuid): ?UserTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())->addUuid($userUuid);
        $userCollectionTransfer = $this->getFactory()->getUserFacade()->getUserCollection(
            (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer),
        );

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }

    /**
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer
     */
    protected function createWarehouseUserAssignmentCollectionRequestTransfer(
        array $formData
    ): WarehouseUserAssignmentCollectionRequestTransfer {
        $warehouseUserAssignmentTransfers = new ArrayObject();

        foreach ($formData[static::WAREHOUSE_USER_FORM_FIELD_UUIDS_WAREHOUSES_TO_ASSIGN] as $warehouseUuid) {
            $warehouseUserAssignmentTransfers->append(
                (new WarehouseUserAssignmentTransfer())
                    ->setUserUuid($formData[static::WAREHOUSE_USER_FORM_FIELD_USER_UUID])
                    ->setWarehouse((new StockTransfer())->setUuid($warehouseUuid)),
            );
        }

        return (new WarehouseUserAssignmentCollectionRequestTransfer())->setWarehouseUserAssignments(
            $warehouseUserAssignmentTransfers,
        );
    }

    /**
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer
     */
    protected function createWarehouseUserAssignmentCollectionDeleteCriteriaTransfer(
        array $formData
    ): WarehouseUserAssignmentCollectionDeleteCriteriaTransfer {
        return (new WarehouseUserAssignmentCollectionDeleteCriteriaTransfer())
            ->setWarehouseUuids($formData[static::WAREHOUSE_USER_FORM_FIELD_UUIDS_WAREHOUSES_TO_DEASSIGN])
            ->setUserUuids([$formData[static::WAREHOUSE_USER_FORM_FIELD_USER_UUID]]);
    }
}
