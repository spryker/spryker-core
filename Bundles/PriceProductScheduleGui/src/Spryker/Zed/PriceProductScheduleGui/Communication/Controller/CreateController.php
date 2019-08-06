<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-01
 * Time: 15:25
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;


use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    public function indexAction(Request $request)
    {
        $priceProductScheduleFormDataProvider = $this->getFactory()->createPriceProductScheduleFormDataProvider();

        $form = $this->getFactory()->createPriceProductScheduleForm($priceProductScheduleFormDataProvider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'title' => $this->getTitleFromRequest($request),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getTitleFromRequest(Request $request): string
    {
        $idProductAbstract = $request->query->get('idProductAbstract');
        if ($request->query->get('idProductAbstract') !== null) {
            return 'Edit Product Abstract: ' . $idProductAbstract;
        }

        $idProductConcrete = $request->query->get('idProduct');

        return 'Edit Product Concrete: ' . $idProductConcrete;
    }
}
