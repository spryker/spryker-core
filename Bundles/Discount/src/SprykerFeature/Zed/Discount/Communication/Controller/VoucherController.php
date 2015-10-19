<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use Generated\Shared\Discount\VoucherCreateInfoInterface;
use Generated\Shared\Transfer\VoucherTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;
use SprykerFeature\Zed\Discount\Communication\Form\VoucherForm;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Communication\Table\DiscountVoucherCodesTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableParameters;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 * @method DiscountFacade getFacade()
 */
class VoucherController extends AbstractController
{
    const SESSION_TIME = 'session_title';
    const ID_POOL_PARAMETER = 'id-pool';
    const BATCH_PARAMETER = 'batch';
    const GENERATED_ON_PARAMETER = 'generated-on';

    const MESSAGE_TYPE_SUCCESS = 'success';

    /**
     * @return array|RedirectResponse
     */
    public function createSingleAction()
    {
        $form = $this->getDependencyContainer()->createVoucherForm();
        $form->handleRequest();

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
                    (int) $formData[VoucherForm::FIELD_DISCOUNT_VOUCHER_POOL],
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
     * @return array|RedirectResponse
     */
    public function createMultipleAction()
    {
        $form = $this->getDependencyContainer()->createVoucherForm(true);
        $form->handleRequest();

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
                    (int) $formData[VoucherForm::FIELD_DISCOUNT_VOUCHER_POOL],
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
     * @param VoucherCreateInfoInterface $voucherCreateInfoInterface
     *
     * @return self
     */
    protected function addVoucherCreateMessage(VoucherCreateInfoInterface $voucherCreateInfoInterface)
    {
        if ($voucherCreateInfoInterface->getType() === self::MESSAGE_TYPE_SUCCESS) {
            return $this->addSuccessMessage($voucherCreateInfoInterface->getMessage());
        }

        return $this->addErrorMessage($voucherCreateInfoInterface->getMessage());
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idPool = $request->query->get(self::ID_POOL_PARAMETER);
        $batchValue = $request->query->get(self::BATCH_PARAMETER);

        $pool = $this->getDependencyContainer()
            ->getVoucherPoolById($idPool)
        ;

        $discount = $this->getDependencyContainer()
            ->getDiscountByIdDiscountVoucherPool($idPool)
        ;

        $countVouchers = $this->getDependencyContainer()
            ->getGeneratedVouchersCountByIdPool($pool->getIdDiscountVoucherPool())
        ;

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

    public function tableAction(Request $request)
    {
        $table = $this->getGeneratedCodesTable($request);

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return DiscountVoucherCodesTable
     */
    protected function getGeneratedCodesTable(Request $request)
    {
        $idPool = $request->query->get(self::ID_POOL_PARAMETER);
        $batch = $request->query->get(self::BATCH_PARAMETER);

        $tableParameters = TableParameters::getTableParameters($request);

        return $this->getDependencyContainer()->createDiscountVoucherCodesTable($tableParameters, $idPool, $batch);
    }



    /**
     * @param Request $request
     *
     * @return Response
     */
    public function exportAction(Request $request)
    {
        $idPool = $request->query->get(self::ID_POOL_PARAMETER);
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
        $store = $this->getDependencyContainer()->getStore();
        $now = new \DateTime('now', new \DateTimeZone($store->getTimezone()));

        return $now;
    }

    /**
     * @return Session
     */
    private function getSession()
    {
        return $this->getApplication()['request']->getSession();
    }

    /**
     * @param int $idPool
     * @param int $batchValue
     *
     * @return Response
     */
    protected function generateCsvFromVouchers($idPool, $batchValue)
    {
        $generatedVouchersQuery = $this->getDependencyContainer()
            ->getQueryForGeneratedVouchersByIdPool($idPool)
        ;

        if ($batchValue > 0) {
            $generatedVouchersQuery = $generatedVouchersQuery->filterByVoucherBatch($batchValue);
        }

        $generatedVouchers = $generatedVouchersQuery
            ->find()
        ;

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
