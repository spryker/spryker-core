<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
abstract class AbstractUpdateProductController extends AbstractController
{
    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'The Product is saved.';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_ERROR = 'Please resolve all errors.';

    /**
     * @var array
     */
    protected const DEFAULT_INITIAL_DATA = [
        GuiTableEditableInitialDataTransfer::DATA => [],
        GuiTableEditableInitialDataTransfer::ERRORS => [],
    ];

    /**
     * @param string $tableViewName
     * @param array|null $requestTableData
     *
     * @return array<mixed>
     */
    protected function getDefaultInitialData(string $tableViewName, ?array $requestTableData): array
    {
        if (!$requestTableData) {
            return static::DEFAULT_INITIAL_DATA;
        }

        $requestTableData = $this->getFactory()->getUtilEncodingService()->decodeJson(
            $requestTableData[$tableViewName],
            true,
        );

        if (!$requestTableData) {
            return static::DEFAULT_INITIAL_DATA;
        }

        $defaultInitialData = static::DEFAULT_INITIAL_DATA;
        $defaultInitialData[GuiTableEditableInitialDataTransfer::DATA] = $requestTableData;

        return $defaultInitialData;
    }

    /**
     * @param array<mixed> $responseData
     *
     * @return array<mixed>
     */
    protected function addSuccessResponseDataToResponse(array $responseData): array
    {
        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addSuccessNotification(static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS)
            ->addActionCloseDrawer()
            ->addActionRefreshTable()
            ->createResponse();

        return array_merge($responseData, $zedUiFormResponseTransfer->toArray());
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     * @param array $responseData
     *
     * @return array
     */
    protected function addErrorResponseDataToResponse(
        FormInterface $form,
        ValidationResponseTransfer $validationResponseTransfer,
        array $responseData
    ): array {
        $zedUiFormResponseBuilder = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder();

        if (!$form->isValid() || !$validationResponseTransfer->getIsSuccess()) {
            $zedUiFormResponseBuilder->addErrorNotification(static::RESPONSE_NOTIFICATION_MESSAGE_ERROR);
        }

        if (!$validationResponseTransfer->getIsSuccess()) {
            foreach ($validationResponseTransfer->getValidationErrors() as $validationErrorTransfer) {
                $zedUiFormResponseBuilder->addErrorNotification($validationErrorTransfer->getMessageOrFail());
            }
        }

        return array_merge($responseData, $zedUiFormResponseBuilder->createResponse()->toArray());
    }
}
