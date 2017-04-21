<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FactFinder\Communication\FactFinderCommunicationFactory getFactory()
 * @method \Spryker\Zed\FactFinder\Business\FactFinderFacade getFacade()
 */
class CsvController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {

        $locale = $request->query->get('locale', '');
        $fileName = $this->getFactory()
            ->getFileName($locale);
        $content = $this->getFactory()
            ->getFileContent($locale);

        if ($content === false) {
            return $this->streamedResponse(function() {}, 404);
        }

        $this->streamedResponse(function() use ($content) {
            echo $content;
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

}
