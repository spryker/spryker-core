<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Controller\Action;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractWidgetController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return mixed
     */
    abstract protected function initialize(Request $request);

    /**
     * @param $type
     * @param $element
     *
     * @return array
     */
    protected function renderWidget($type, $element)
    {
        return $this->viewResponse([
            $type => $element,
            'alternativeRoute' => 'Application/Widget/' . $type,
        ]);
    }

}
