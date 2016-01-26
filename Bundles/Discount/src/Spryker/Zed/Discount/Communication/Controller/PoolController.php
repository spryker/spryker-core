<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\VoucherCodesTransfer;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherPoolCategoryTableMap;
use Propel\Runtime\Map\TableMap;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Application\Business\Url\Url;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Communication\DiscountCommunicationFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method DiscountCommunicationFactory getFactory()
 * @method DiscountQueryContainer getQueryContainer()
 * @method DiscountFacade getFacade()
 */
class PoolController extends AbstractController
{

    const TERM = 'term';
    const BLANK = '';

    public function createAction(Request $request)
    {
        $form = $this->getFactory()->createVoucherCodesForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $voucherCodesTransfer = (new VoucherCodesTransfer())->fromArray($formData, true);

            $voucherPoolTransfer = $this->getFacade()->saveVoucherCode($voucherCodesTransfer);

            $url = Url::generate(DiscountConstants::URL_DISCOUNT_POOL_EDIT, [
                DiscountConstants::PARAM_ID_POOL => $voucherPoolTransfer->getIdDiscountVoucherPool(),
            ]);

            return $this->redirectResponse($url->__toString());
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
    public function editAction(Request $request)
    {
        $form = $this->getFactory()->createVoucherCodesForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $voucherCodesTransfer = (new VoucherCodesTransfer())->fromArray($formData, true);
            $voucherPoolTransfer = $this->getFacade()->saveVoucherCode($voucherCodesTransfer);

            $url = Url::generate(DiscountConstants::URL_DISCOUNT_POOL_EDIT, [
                DiscountConstants::PARAM_ID_POOL => $voucherPoolTransfer->getIdDiscountVoucherPool(),
            ]);

            return $this->redirectResponse($url->__toString());
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
    public function editCategoryAction(Request $request)
    {
        $idPoolCategory = $request->query->get('id', 0);

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
     * @return JsonResponse
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
     * @return JsonResponse
     */
    public function poolTableAction()
    {
        $table = $this->getFactory()->createVoucherPoolTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
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
