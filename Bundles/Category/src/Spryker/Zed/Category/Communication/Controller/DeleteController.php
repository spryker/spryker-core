<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Business\CategoryFacade getFacade()
 */
class DeleteController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCategory = $this->castId($request->get(CategoryConstants::PARAM_ID_CATEGORY));

        $form = $this->getFactory()->createCategoryDeleteForm($idCategory);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $this
                ->getFacade()
                ->delete($data['fk_category']);

            return $this->redirectResponse('/category/root');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

}
