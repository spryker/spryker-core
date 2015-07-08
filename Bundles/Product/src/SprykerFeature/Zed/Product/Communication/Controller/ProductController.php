<?php
/**
 * Created by PhpStorm.
 * User: vsevoloddolgopolov
 * Date: 02.07.15
 * Time: 17:41
 */

namespace SprykerFeature\Zed\Product\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use SprykerEngine\Zed\Gui\Business\ProductForm;
use SprykerEngine\Zed\Kernel\Locator;

class ProductController extends AbstractController
{

    public function indexAction(Request $request)
    {
        $error = false;

        $form =['product_form'];



//      if ($request->isXmlHttpRequest()) {
//            return $this->jsonResponse([
//                'results' => [
//                    ['id' => 1, 'text' => 'asd'],
//                    ['id' => 2, 'text' => 'asdasd'],
//                ],
//                'more' => false
//            ]);
//        }

        if ($request->isMethod('POST')) {
            if(false === $data = $form->handleRequestAndData($request)){
                $error = $form->getErrors();
            }

        }


        return $this->viewResponse([
            'form' => $form->render(),
            'error' => $error,
        ]);
    }


}
