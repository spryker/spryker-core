<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\FactFinder\Controller;

use Spryker\Yves\Application\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\FactFinder\FactFinderFactory getFactory()
 * @method \Spryker\Client\FactFinder\FactFinderClientInterface getClient()
 */
class IndexController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $searchString = $request->query->get('q', '');

        $ffSearchResponseTransfer = $this->getClient()->search();

        return [];
    }

}
