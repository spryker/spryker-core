<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\VoucherCreateTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;
use SprykerFeature\Zed\Discount\Communication\Form\VoucherForm;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
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
    const GENERATED_ON_PARAMETER = 'generated-on';

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

            $voucherCreateTransfer = new VoucherCreateTransfer();
            $voucherCreateTransfer->fromArray($formData, true);
            $voucherCreateTransfer->setAmount(VoucherForm::ONE_VOUCHER);
            $voucherCreateTransfer->setIncludeTemplate(false);

            $this->getFacade()->createVoucherCodes($voucherCreateTransfer);

            return $this->redirectResponse('/discount/voucher/view/?' . self::ID_POOL_PARAMETER . '=' . (int) $formData[VoucherForm::FIELD_ID_POOL]);
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

            $voucherCreateTransfer = new VoucherCreateTransfer();
            $voucherCreateTransfer->fromArray($formData, true);
            $voucherCreateTransfer->setIncludeTemplate(false);

            $this->getFacade()->createVoucherCodes($voucherCreateTransfer);

            return $this->redirectResponse('/discount/voucher/view/?' . self::ID_POOL_PARAMETER . '=' . (int) $formData[VoucherForm::FIELD_ID_POOL]);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idPool = $request->query->get(self::ID_POOL_PARAMETER);


        $pool = $this->getDependencyContainer()
            ->getVoucherPoolById($idPool)
        ;

        $discount = $this->getDependencyContainer()
            ->getDiscountByIdDiscountVoucherPool($idPool)
        ;

        $countVouchers = $this->getDependencyContainer()
            ->getGeneratedVouchersCountByIdPool($pool->getIdDiscountVoucherPool())
        ;

        return $this->viewResponse([
            'idPool' => $idPool,
            'pool' => $pool,
            'discount' => $discount,
            'countVouchers' => $countVouchers,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function exportAction(Request $request)
    {
        $idPool = $request->query->get(self::ID_POOL_PARAMETER);
        $timestamp = $request->query->get(self::GENERATED_ON_PARAMETER);
        $store = $this->getDependencyContainer()->getStore();

        $createdAt = new \DateTime('now', new \DateTimeZone($store->getTimezone()));
        $createdAt->setTimestamp($timestamp);

        return $this->generateCsvFromVouchers($idPool, $createdAt);
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
     *
     * @return Response
     */
    protected function generateCsvFromVouchers($idPool)
    {
        $generatedVouchers = $this->getDependencyContainer()
            ->getQueryForGeneratedVouchersByIdPool($idPool)
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
