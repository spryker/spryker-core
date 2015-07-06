<?php
/**
 * Created by PhpStorm.
 * User: dsavin
 * Date: 02.07.15
 * Time: 18:56
 */

namespace SprykerFeature\Zed\UIExample\Communication\Controller;


use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

use SprykerFeature\Zed\UiExample\Communication\Table\UiExampleTable;
use Symfony\Component\HttpFoundation\Request;

class SandboxController extends AbstractController
{
    public function indexAction(Request $request)
    {

        $data = [
            ['a', 'b', 'c'],
            ['d', 'e'],
        ];
        $table = new UiExampleTable($data);

        $table->render();

        return $this->viewResponse(['table' => $table]);
    }
}

