<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 */
class VoucherController extends AbstractController
{
    public const URL_PARAM_ID_POOL = 'id-pool';
    public const URL_PARAM_ID_DISCOUNT = 'id-discount';
    public const URL_PARAM_ID_VOUCHER = 'id-voucher';
    public const CSV_FILENAME = 'vouchers.csv';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteDiscountVouchersAction(Request $request)
    {
        $idDiscount = $this->castId($request->query->get(self::URL_PARAM_ID_DISCOUNT));
        $idPool = $this->castId($request->query->get(self::URL_PARAM_ID_POOL));

        $affectedRows = $this->getQueryContainer()
            ->queryVouchersByIdVoucherPool($idPool)
            ->delete();

        if ($affectedRows > 0) {
            $this->addSuccessMessage(
                sprintf(
                    'Successfully deleted "%d" vouchers.',
                    $affectedRows
                )
            );
        } else {
            $this->addErrorMessage('No voucher codes were deleted.');
        }

        return new RedirectResponse(
            $this->createEditDiscountRedirectUrl($idDiscount)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteVoucherCodeAction(Request $request)
    {
        $idDiscount = $this->castId($request->query->get(self::URL_PARAM_ID_DISCOUNT));
        $idVoucher = $this->castId($request->query->get(self::URL_PARAM_ID_VOUCHER));

        $voucherEntity = $this->getQueryContainer()
            ->queryVoucherByIdVoucher($idVoucher);

        $affectedRows = $voucherEntity->delete();

        if ($affectedRows > 0) {
            $this->addSuccessMessage('Voucher code successfully deleted.');
        } else {
            $this->addErrorMessage('Voucher code could not be deleted.');
        }

        return new RedirectResponse(
            $this->createEditDiscountRedirectUrl($idDiscount)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportAction(Request $request)
    {
        $idPool = $this->castId($request->query->get(self::URL_PARAM_ID_POOL));

        return $this->generateCsvFromVouchers($idPool);
    }

    /**
     * @param int $idPool
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function generateCsvFromVouchers($idPool)
    {
        $generatedVouchers = $this->getQueryContainer()
            ->queryVouchersByIdVoucherPool($idPool)
            ->find();

        $streamedResponse = new StreamedResponse();

        $streamedResponse->setCallback(function () use ($generatedVouchers) {
            $csvHandle = fopen('php://output', 'w+');
            fputcsv($csvHandle, ['Voucher Code']);

            foreach ($generatedVouchers as $voucher) {
                fputcsv($csvHandle, [$voucher->getCode()]);
            }

            fclose($csvHandle);
        });

        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="' . self::CSV_FILENAME . '"');

        return $streamedResponse->send();
    }

    /**
     * @param int $idDiscount
     *
     * @return string
     */
    protected function createEditDiscountRedirectUrl($idDiscount)
    {
        $redirectUrl = Url::generate(
            '/discount/index/edit',
            [
                self::URL_PARAM_ID_DISCOUNT => $idDiscount,
            ]
        )->build();

        return $redirectUrl;
    }
}
