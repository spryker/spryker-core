<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherCodesTransfer;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Communication\Form\PoolForm;
use SprykerFeature\Zed\Discount\Communication\Table\VoucherPoolTable;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use Propel\Runtime\Map\TableMap;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 * @method DiscountQueryContainer getQueryContainer()
 * @method DiscountFacade getFacade()
 */
class PoolController extends AbstractController
{

    const TERM = 'term';
    const BLANK = '';

    public function createAction(Request $request)
    {
        $form = $this->getDependencyContainer()->createCartRuleForm(
            $this->getDependencyContainer()->createVoucherCodesFormType()
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $voucherCodesTransfer = (new VoucherCodesTransfer())->fromArray($formData, true);

            $voucherPoolTransfer = $this->getFacade()->saveVoucherCode($voucherCodesTransfer);

            return $this->redirectResponse(sprintf(
                VoucherPoolTable::URL_DISCOUNT_POOL_EDIT,
                VoucherPoolTable::PARAM_ID_POOL,
                $voucherPoolTransfer->getIdDiscountVoucherPool()
            ));

        }

        return [
            'form' => $form->createView(),
        ];
    }

    public function editAction(Request $request)
    {
        $idPool = $request->query->get(VoucherPoolTable::PARAM_ID_POOL);

        $defaultData = $this->getVoucherCodeDefaultData($idPool);

        $form = $this->getDependencyContainer()->createCartRuleForm(
            $this->getDependencyContainer()->createVoucherCodesFormType(),
            $defaultData->toArray()
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $voucherCodesTransfer = (new VoucherCodesTransfer())->fromArray($formData, true);
            $voucherPoolTransfer = $this->getFacade()->saveVoucherCode($voucherCodesTransfer);

            return $this->redirectResponse(sprintf(
                VoucherPoolTable::URL_DISCOUNT_POOL_EDIT,
                VoucherPoolTable::PARAM_ID_POOL,
                $voucherPoolTransfer->getIdDiscountVoucherPool()
            ));

        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param int $idPoolCategory
     *
     * @return array
     */
    public function createCategoryAction($idPoolCategory = 0)
    {
        $form = $this->getDependencyContainer()->createPoolCategoryForm($idPoolCategory);
        $form->handleRequest();

        if ($form->isValid()) {
            $facade = $this->getFacade();

            $category = new VoucherPoolCategoryTransfer();
            $category->fromArray($form->getData());

            $facade->createDiscountVoucherPoolCategory($category);
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
     * @param Request $request
     *
     * @return array
     */
    public function categoriesAction(Request $request)
    {
        $table = $this->getDependencyContainer()->createPoolCategoriesTable();

        return [
            'categories' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function categoriesTableAction()
    {
        $table = $this->getDependencyContainer()->createPoolCategoriesTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getDependencyContainer()->createVoucherPoolTable();

        return [
            'categories' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function poolTableAction()
    {
        $table = $this->getDependencyContainer()->createVoucherPoolTable();

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
            ->findByName('%' . $term . '%')
        ;

        $result = [];
        if (count($categories) > 0) {
            $names = $categories->toArray(null, false, TableMap::TYPE_COLNAME);

            foreach ($names as $value) {
                $result[] = $value[SpyDiscountVoucherPoolCategoryTableMap::COL_NAME];
            }
        }

        return $this->jsonResponse($result);
    }

    /**
     * @param int $idPool
     *
     * @return array
     */
    protected function getVoucherCodeDefaultData($idPool)
    {
        $discountVoucherPool = $this->getQueryContainer()->queryVoucherCodeByIdVoucherCode($idPool)->findOne();

        $discount = $this->getDiscountByIdVoucherPool($idPool);

        $decisionRules = $this->getQueryContainer()->queryDiscountDecisionRulesByIdPool($discountVoucherPool)->find();

        $defaultData = (new VoucherCodesTransfer())->fromArray($discountVoucherPool->toArray(), true);
        $defaultData->setDecisionRules($decisionRules->toArray());
        $defaultData->setCalculatorPlugin($discount->getCalculatorPlugin());
        $defaultData->setCollectorPlugin($discount->getCollectorPlugin());
        $defaultData->setIsPrivileged((bool) $discount->getIsPrivileged());
        $defaultData->setValidFrom($discount->getValidFrom());
        $defaultData->setValidTo($discount->getValidTo());

        return $defaultData;
    }

    protected function getDiscountByIdVoucherPool($idVoucherPool)
    {
        return $this->getQueryContainer()
            ->queryDiscount()
            ->filterByFkDiscountVoucherPool($idVoucherPool)
            ->findOne()
        ;
    }

}
