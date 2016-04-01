<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\VoucherCreateInfoTransfer;
use Generated\Shared\Transfer\VoucherTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Discount\Communication\Form\VoucherForm;
use Spryker\Zed\Gui\Communication\Table\TableParameters;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 */
class VoucherController extends AbstractController
{

    const SESSION_TIME = 'session_title';
    const ID_POOL_PARAMETER = 'id-pool';
    const BATCH_PARAMETER = 'batch';
    const GENERATED_ON_PARAMETER = 'generated-on';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createSingleAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createVoucherFormDataProvider();
        $form = $this
            ->getFactory()
            ->createVoucherForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $this->setSessionTimestampForVoucherGenerator();

            $voucherTransfer = new VoucherTransfer();
            $voucherTransfer->fromArray($formData, true);
            $voucherTransfer->setQuantity(VoucherForm::ONE_VOUCHER);
            $voucherTransfer->setIncludeTemplate(false);

            $voucherCreateInfoTransfer = $this->getFacade()->createVoucherCodes($voucherTransfer);

            $this->addVoucherCreateMessage($voucherCreateInfoTransfer);

            return $this->redirectResponse(
                sprintf(
                    '/discount/voucher/view/?%s=%d&%s=%d',
                    self::ID_POOL_PARAMETER,
                    (int)$formData[VoucherForm::FIELD_DISCOUNT_VOUCHER_POOL],
                    self::BATCH_PARAMETER,
                    $voucherTransfer->getVoucherBatch()
                )
            );
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createMultipleAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createVoucherFormDataProvider();
        $form = $this
            ->getFactory()
            ->createVoucherForm(
                $dataProvider->getData(true),
                $dataProvider->getOptions(true)
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();
            $this->setSessionTimestampForVoucherGenerator();

            $voucherTransfer = new VoucherTransfer();
            $voucherTransfer->fromArray($formData, true);
            $voucherTransfer->setIncludeTemplate(false);

            $voucherCreateInfoTransfer = $this->getFacade()->createVoucherCodes($voucherTransfer);

            $this->addVoucherCreateMessage($voucherCreateInfoTransfer);

            return $this->redirectResponse(
                sprintf(
                    '/discount/voucher/view/?%s=%d&%s=%d',
                    self::ID_POOL_PARAMETER,
                    (int)$formData[VoucherForm::FIELD_DISCOUNT_VOUCHER_POOL],
                    self::BATCH_PARAMETER,
                    $voucherTransfer->getVoucherBatch()
                )
            );
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * VoucherCreateInfoTransfer might have different types of a message, success and error messages are mapped respectively
     * onto respective types of the MessengerFacade, other types of messages are mapped to Info message type.
     *
     * @param \Generated\Shared\Transfer\VoucherCreateInfoTransfer $voucherCreateInfoInterface
     *
     * @return $this
     */
    protected function addVoucherCreateMessage(VoucherCreateInfoTransfer $voucherCreateInfoInterface)
    {
        if ($voucherCreateInfoInterface->getType() === DiscountConstants::MESSAGE_TYPE_SUCCESS) {
            return $this->addSuccessMessage($voucherCreateInfoInterface->getMessage());
        }
        if ($voucherCreateInfoInterface->getType() === DiscountConstants::MESSAGE_TYPE_ERROR) {
            return $this->addErrorMessage($voucherCreateInfoInterface->getMessage());
        }

        return $this->addInfoMessage($voucherCreateInfoInterface->getMessage());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idPool = $this->castId($request->query->get(self::ID_POOL_PARAMETER));
        $batchValue = $request->query->get(self::BATCH_PARAMETER);

        $pool = $this->getFactory()
            ->getVoucherPoolById($idPool);

        $discount = $this->getFactory()
            ->getDiscountByIdDiscountVoucherPool($idPool);

        $countVouchers = $this->getFactory()
            ->getGeneratedVouchersCountByIdPool($pool->getIdDiscountVoucherPool());

        $generatedCodesTable = $this->getGeneratedCodesTable($request);

        return $this->viewResponse([
            'idPool' => $idPool,
            'pool' => $pool,
            'batchValue' => $batchValue,
            'discount' => $discount,
            'countVouchers' => $countVouchers,
            'generatedCodes' => $generatedCodesTable->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request)
    {
        $table = $this->getGeneratedCodesTable($request);

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Zed\Discount\Communication\Table\DiscountVoucherCodesTable
     */
    protected function getGeneratedCodesTable(Request $request)
    {
        $idPool = $this->castId($request->query->get(self::ID_POOL_PARAMETER));
        $batch = $request->query->get(self::BATCH_PARAMETER);

        $tableParameters = TableParameters::getTableParameters($request);

        return $this->getFactory()->createDiscountVoucherCodesTable($tableParameters, $idPool, $batch);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportAction(Request $request)
    {
        $idPool = $this->castId($request->query->get(self::ID_POOL_PARAMETER));
        $batch = $request->query->get(self::BATCH_PARAMETER);

        return $this->generateCsvFromVouchers($idPool, $batch);
    }

    /**
     * @return \DateTime
     */
    protected function setSessionTimestampForVoucherGenerator()
    {
        $now = $this->getCurrentTimestampByStoreTimeZone();
        $this->getSession()->set(self::SESSION_TIME, $now);

        return $now;
    }

    /**
     * @return \DateTime
     */
    protected function getCurrentTimestampByStoreTimeZone()
    {
        $store = $this->getFactory()->getStore();
        $now = new \DateTime('now', new \DateTimeZone($store->getTimezone()));

        return $now;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    private function getSession()
    {
        return $this->getApplication()['request']->getSession();
    }

    /**
     * @param int $idPool
     * @param int $batchValue
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function generateCsvFromVouchers($idPool, $batchValue)
    {
        $generatedVouchersQuery = $this->getFactory()
            ->getQueryForGeneratedVouchersByIdPool($idPool);

        if ($batchValue > 0) {
            $generatedVouchersQuery = $generatedVouchersQuery->filterByVoucherBatch($batchValue);
        }

        $generatedVouchers = $generatedVouchersQuery
            ->find();

        $response = new StreamedResponse();

        $response->setCallback(function () use ($generatedVouchers) {
            $csvHandle = fopen('php://output', 'w+');
            fputcsv($csvHandle, ['Voucher Code']);

            foreach ($generatedVouchers as $voucher) {
                fputcsv($csvHandle, [$voucher->getCode()]);
            }

            fclose($csvHandle);
        });

        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="vouchers.csv"');

        return $response->send();
    }

}
