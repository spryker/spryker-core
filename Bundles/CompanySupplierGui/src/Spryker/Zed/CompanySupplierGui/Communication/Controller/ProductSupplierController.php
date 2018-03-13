<?php


namespace Spryker\Zed\CompanySupplierGui\Communication\Controller;


use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanySupplierGui\Communication\CompanySupplierGuiCommunicationFactory getFactory()
 */
class ProductSupplierController extends AbstractController
{
    public function indexAction()
    {
        $productSuppliersTable = $this->getFactory()->createProductSuppliersTable();

        return [
            'productSuppliersTable' => $productSuppliersTable->render()
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request)
    {
        $productSuppliersTable = $this
            ->getFactory()
            ->createProductSuppliersTable();

        return $this->jsonResponse(
            $productSuppliersTable->fetchData()
        );
    }
}