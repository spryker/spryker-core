<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\MerchantRelationshipThresholdType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\AbstractMerchantRelationshipThresholdType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\MerchantRelationshipSalesOrderThresholdGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Persistence\MerchantRelationshipSalesOrderThresholdGuiRepositoryInterface getRepository()
 */
class EditController extends AbstractController
{
    protected const PARAM_STORE_CURRENCY_REQUEST = 'store_currency';
    protected const REQUEST_ID_MERCHANT_RELATIONSHIP = 'id-merchant-relationship';
    protected const MESSAGE_UPDATE_SUCCESSFUL = 'The Merchant Relationship Thresholds is saved successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $storeCurrencyRequestParam = $request->query->get(static::PARAM_STORE_CURRENCY_REQUEST);
        $idMerchantRelationship = $request->query->getInt(static::REQUEST_ID_MERCHANT_RELATIONSHIP);

        $currencyTransfer = $this->getCurrencyTransferFromRequestParam($storeCurrencyRequestParam);
        $storeTransfer = $this->getStoreTransferFromRequestParam($storeCurrencyRequestParam);

        $thresholdForm = $this->getFactory()->createThresholdForm(
            $idMerchantRelationship,
            $storeTransfer,
            $currencyTransfer
        );
        $thresholdForm->handleRequest($request);

        if ($thresholdForm->isSubmitted() && $thresholdForm->isValid()) {
            return $this->handleFormSubmission($request, $thresholdForm, $idMerchantRelationship, $storeTransfer, $currencyTransfer);
        }

        $viewData = $this->executeIndexAction($idMerchantRelationship, $thresholdForm);

        return $this->viewResponse($viewData);
    }

    /**
     * @param int $idMerchantRelationship
     * @param \Symfony\Component\Form\FormInterface $thresholdForm
     *
     * @return array
     */
    protected function executeIndexAction(int $idMerchantRelationship, FormInterface $thresholdForm): array
    {
        $localeCollection = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        $merchantRelationshipTransfer = $this->getFactory()
            ->getMerchantRelationshipFacade()
            ->getMerchantRelationshipById(
                $this->createMerchantRelationshipTransfer($idMerchantRelationship)
            );

        $companyBusinessUnit = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit();

        $companyBusinessUnit->setCompany(
            $this->getFactory()
                ->getCompanyFacade()
                ->getCompanyById(
                    $this->createCompanyTransfer($companyBusinessUnit->getFkCompany())
                )
        );

        $merchantRelationshipTransfer->setOwnerCompanyBusinessUnit($companyBusinessUnit);

        return [
            'localeCollection' => $localeCollection,
            'merchantRelationshipThresholdform' => $thresholdForm->createView(),
            'merchantRelationship' => $merchantRelationshipTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $thresholdForm
     * @param int $idMerchantRelationship
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleFormSubmission(
        Request $request,
        FormInterface $thresholdForm,
        int $idMerchantRelationship,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): RedirectResponse {
        $data = $thresholdForm->getData();

        $this->handleThresholdData(
            $data[MerchantRelationshipThresholdType::FIELD_HARD],
            MerchantRelationshipSalesOrderThresholdGuiConfig::GROUP_HARD,
            $idMerchantRelationship,
            $storeTransfer,
            $currencyTransfer
        );

        $this->handleThresholdData(
            $data[MerchantRelationshipThresholdType::FIELD_SOFT],
            MerchantRelationshipSalesOrderThresholdGuiConfig::GROUP_SOFT,
            $idMerchantRelationship,
            $storeTransfer,
            $currencyTransfer
        );

        $this->addSuccessMessage(static::MESSAGE_UPDATE_SUCCESSFUL);

        return $this->redirectResponse($request->getRequestUri());
    }

    /**
     * @param array $thresholdData
     * @param string $strategyGroup
     * @param int $idMerchantRelationship
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return void
     */
    protected function handleThresholdData(
        array $thresholdData,
        string $strategyGroup,
        int $idMerchantRelationship,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): void {
        $merchantRelationshipSalesOrderThresholdTransfer = $this->createMerchantRelationshipSalesOrderThresholdTransfer(
            $thresholdData[AbstractMerchantRelationshipThresholdType::FIELD_ID_THRESHOLD] ?? null,
            $idMerchantRelationship,
            $storeTransfer,
            $currencyTransfer
        );

        if ($this->canMapThresholdData($thresholdData, $strategyGroup)) {
            $merchantRelationshipSalesOrderThresholdTransfer = $this->getFactory()
                ->createMerchantRelationshipThresholdFormMapperResolver()
                ->resolveMerchantRelationshipThresholdFormMapperClassInstanceByStrategyGroup($strategyGroup)
                ->mapFormDataToTransfer($thresholdData, $merchantRelationshipSalesOrderThresholdTransfer);
        }

        $this->saveMerchantRelationshipSalesOrderThreshold($merchantRelationshipSalesOrderThresholdTransfer);
    }

    /**
     * @param array $thresholdData
     * @param string $strategyGroup
     *
     * @return bool
     */
    protected function canMapThresholdData(array $thresholdData, string $strategyGroup): bool
    {
        if (!isset($thresholdData[AbstractMerchantRelationshipThresholdType::FIELD_STRATEGY])) {
            return false;
        }

        return $this->getFactory()
            ->createMerchantRelationshipThresholdFormMapperResolver()
            ->hasMerchantRelationshipThresholdFormMapperByStrategyGroup(
                $strategyGroup
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return void
     */
    protected function saveMerchantRelationshipSalesOrderThreshold(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): void {
        if (empty($merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getThreshold())) {
            if ($merchantRelationshipSalesOrderThresholdTransfer->getIdMerchantRelationshipSalesOrderThreshold()) {
                $this->getFactory()
                    ->getMerchantRelationshipSalesOrderThresholdFacade()
                    ->deleteMerchantRelationshipSalesOrderThreshold($merchantRelationshipSalesOrderThresholdTransfer);
            }

            return;
        }

        $this->getFactory()
            ->getMerchantRelationshipSalesOrderThresholdFacade()
            ->saveMerchantRelationshipSalesOrderThreshold($merchantRelationshipSalesOrderThresholdTransfer);
    }

    /**
     * @param string|null $storeCurrencyRequestParam
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransferFromRequestParam(?string $storeCurrencyRequestParam): CurrencyTransfer
    {
        return $this->getFactory()
            ->createStoreCurrencyFinder()
            ->getCurrencyTransferFromRequestParam($storeCurrencyRequestParam);
    }

    /**
     * @param string|null $storeCurrencyRequestParam
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreTransferFromRequestParam(?string $storeCurrencyRequestParam): StoreTransfer
    {
        return $this->getFactory()
            ->createStoreCurrencyFinder()
            ->getStoreTransferFromRequestParam($storeCurrencyRequestParam);
    }

    /**
     * @param int|null $idMerchantRelationshipSalesOrderThreshold
     * @param int $idMerchantRelationship
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    protected function createMerchantRelationshipSalesOrderThresholdTransfer(
        ?int $idMerchantRelationshipSalesOrderThreshold,
        int $idMerchantRelationship,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        return (new MerchantRelationshipSalesOrderThresholdTransfer())
            ->setIdMerchantRelationshipSalesOrderThreshold($idMerchantRelationshipSalesOrderThreshold)
            ->setStore($storeTransfer)
            ->setCurrency($currencyTransfer)
            ->setSalesOrderThresholdValue(new SalesOrderThresholdValueTransfer())
            ->setMerchantRelationship(
                $this->createMerchantRelationshipTransfer($idMerchantRelationship)
            );
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function createMerchantRelationshipTransfer(int $idMerchantRelationship): MerchantRelationshipTransfer
    {
        return (new MerchantRelationshipTransfer())->setIdMerchantRelationship($idMerchantRelationship);
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    protected function createCompanyTransfer(int $idCompany): CompanyTransfer
    {
        return (new CompanyTransfer())->setIdCompany($idCompany);
    }
}
