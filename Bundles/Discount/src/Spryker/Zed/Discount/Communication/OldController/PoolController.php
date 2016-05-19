<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\VoucherCodesTransfer;
use Propel\Runtime\Map\TableMap;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 */
class PoolController extends AbstractController
{

    const TERM = 'term';
    const BLANK = '';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->buildVoucherForm()->handleRequest($request);

        return $this->processVoucherForm($form, 'The voucher has been created.');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idPool = $this->castId($request->query->get(DiscountConstants::PARAM_ID_POOL));
        $form = $this->buildVoucherForm($idPool)->handleRequest($request);

        return $this->processVoucherForm($form, 'The voucher has been saved.');
    }

    /**
     * @param int|null $idPool
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function buildVoucherForm($idPool = null)
    {
        $dataProvider = $this->getFactory()->createVoucherCodesFormDataProvider();
        $form = $this
            ->getFactory()
            ->createVoucherCodesForm(
                $dataProvider->getData($idPool),
                $dataProvider->getOptions()
            );
        return $form;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param string $successMessage
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processVoucherForm(FormInterface $form, $successMessage)
    {
        if ($form->isValid()) {
            $formData = $form->getData();

            $voucherCodesTransfer = (new VoucherCodesTransfer())->fromArray($formData, true);

            $voucherPoolTransfer = $this->getFacade()->saveVoucherCode($voucherCodesTransfer);

            $url = Url::generate(DiscountConstants::URL_DISCOUNT_POOL_EDIT, [
                DiscountConstants::PARAM_ID_POOL => $voucherPoolTransfer->getIdDiscountVoucherPool(),
            ]);
            $this->addSuccessMessage($successMessage);

            return $this->redirectResponse($url->build());
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function editCategoryAction(Request $request)
    {
        $idPoolCategory = $this->castId($request->query->get('id', 0));

        return $this->createCategoryAction($idPoolCategory);
    }

    /**
     * @return array
     */
    public function categoriesAction()
    {
        $table = $this->getFactory()->createPoolCategoriesTable();

        return [
            'categories' => $table->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function categoriesTableAction()
    {
        $table = $this->getFactory()->createPoolCategoriesTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createVoucherPoolTable();

        return [
            'categories' => $table->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function poolTableAction()
    {
        $table = $this->getFactory()->createVoucherPoolTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function categorySuggestAction(Request $request)
    {
        $term = $request->get(self::TERM);

        $categories = $this->getQueryContainer()
            ->queryDiscountVoucherPoolCategory()
            ->findByName('%' . $term . '%');

        $result = [];
        if (count($categories) > 0) {
            $names = $categories->toArray(null, false, TableMap::TYPE_COLNAME);

            foreach ($names as $value) {
                $result[] = $value[SpyDiscountVoucherPoolCategoryTableMap::COL_NAME];
            }
        }

        return $this->jsonResponse($result);
    }

}
