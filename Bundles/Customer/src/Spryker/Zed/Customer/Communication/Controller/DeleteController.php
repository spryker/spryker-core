<?php


namespace Spryker\Zed\Customer\Communication\Controller;


use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacade getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class DeleteController extends AbstractController
{
    public function indexAction(Request $request)
    {
        $idCustomer = $this->castId($request->query->get(CustomerConstants::PARAM_ID_CUSTOMER));

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        try {
            $customerTransfer = $this->getFacade()->getCustomer($customerTransfer);
        } catch (CustomerNotFoundException $exception) {
            $this->addErrorMessage('Customer does not exist');
            return $this->redirectResponse('/customer');
        }

        return $this->viewResponse([
            'idCustomer' => $customerTransfer->getIdCustomer()
        ]);
    }

    public function confirmAction(Request $request)
    {
        $idCustomer = $this->castId($request->query->get(CustomerConstants::PARAM_ID_CUSTOMER));

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        try {
            $customerTransfer = $this->getFacade()->getCustomer($customerTransfer);
        } catch (CustomerNotFoundException $exception) {
            $this->addErrorMessage('Customer does not exist');
            return $this->redirectResponse('/customer');
        }

        if (!$this->getFacade()->anonymizeCustomer($customerTransfer)) {
            $this->addErrorMessage('Customer could not be deleted');
            return $this->redirectResponse((string)new Url([
                'path' => '/customer/delete',
                'query' => [
                    'id-customer' => $customerTransfer->getIdCustomer()
                ]
            ]));
        }

        $this->addSuccessMessage('Customer successfully deleted');
        return $this->redirectResponse('/customer');
    }

}