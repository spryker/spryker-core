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

        $priceProductScheduleForm = $this->getFactory()->createPriceProductScheduleForm($priceProductScheduleFormDataProvider);

        return $this->viewResponse([
            'form' => $priceProductScheduleForm->createView(),
        ]);
    }
}
