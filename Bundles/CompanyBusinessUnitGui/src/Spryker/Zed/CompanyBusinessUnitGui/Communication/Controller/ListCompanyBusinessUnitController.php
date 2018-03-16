<?php


namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Controller;


use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 */
class ListCompanyBusinessUnitController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $companyBusinessUnitTable = $this->getFactory()
            ->createCompanyBusinessUnitTable();

        return $this->viewResponse([
            'companyBusinessUnitTable' => $companyBusinessUnitTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createCompanyBusinessUnitTable();

        return $this->jsonResponse($table->fetchData());
    }
}