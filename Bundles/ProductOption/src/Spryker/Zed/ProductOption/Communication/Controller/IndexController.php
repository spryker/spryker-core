<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionRepositoryInterface getRepository()
 */
class IndexController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function toggleActiveAction(Request $request)
    {
        $idDiscount = $this->castId($request->query->get(BaseOptionController::URL_PARAM_ID_PRODUCT_OPTION_GROUP));
        $isActive = $request->query->get(BaseOptionController::URL_PARAM_ACTIVE);
        $redirectUrl = $request->query->get(BaseOptionController::URL_PARAM_REDIRECT_URL);

        $isChanged = $this->getFacade()->toggleOptionActive($idDiscount, (bool)$isActive);

        if ($isChanged === false) {
            $this->addErrorMessage('Could not activate option.');
        } else {
            $this->addSuccessMessage(sprintf(
                'Option successfully %s.',
                $isActive ? 'activated' : 'deactivated'
            ));
        }

        return new RedirectResponse($redirectUrl);
    }
}
