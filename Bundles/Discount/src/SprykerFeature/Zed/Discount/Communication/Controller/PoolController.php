<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Communication\Form\PoolCategoryForm;
use SprykerFeature\Zed\Discount\Communication\Form\PoolForm;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use Propel\Runtime\Map\TableMap;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;

/**
 * @method VoucherDependencyContainer getDependencyContainer()
 * @method DiscountQueryContainer getQueryContainer()
 * @var DiscountFacade $facade
 */
class PoolController extends AbstractController
{

    const TERM = 'term';
    const BLANK = '';

    public function createAction($idPoolCategory = 0)
    {
        $form = $this->getDependencyContainer()->createPoolForm($idPoolCategory);
        $form->handleRequest();

        if ($form->isValid()) {
            $facade = $this->getFacade();
            $formData = $form->getData();

            $pool = new VoucherPoolTransfer();
            $pool->fromArray($formData, true);

            $category = $this->getQueryContainer()
                ->queryDiscountVoucherPoolCategory()
                ->findOneByName($formData[PoolForm::VOUCHER_POOL_CATEGORY])
            ;

            $pool->setFkDiscountVoucherPoolCategory($category->getIdDiscountVoucherPoolCategory());
            $pool = $facade->createDiscountVoucherPool($pool);

            $discount = new DiscountTransfer();
            $discount->fromArray($formData, true);
            $discount->setDisplayName(self::BLANK);
            $discount->setCollectorPlugin(self::BLANK);
            $discount->setFkDiscountVoucherPool($pool->getIdDiscountVoucherPool());

            $facade->createDiscount($discount);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
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
//    public function editCategoryAction()
//    {
//        $idPoolCategory = $request->query->get('id', 0);
//
//        return $this->createCategoryAction($idPoolCategory);
//    }

    /**
     * @param Request $request
     *
     * @return array
     */
//    public function categoriesAction(Request $request)
//    {
//        $table = $this->getDependencyContainer()->createPoolCategoriesTable();
//
//        return [
//            'categories' => $table->render(),
//        ];
//    }

    /**
     * @return JsonResponse
     */
//    public function categoriesTableAction()
//    {
//        $table = $this->getDependencyContainer()->createPoolCategoriesTable();
//
//        return $this->jsonResponse(
//            $table->fetchData()
//        );
//    }

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

}
