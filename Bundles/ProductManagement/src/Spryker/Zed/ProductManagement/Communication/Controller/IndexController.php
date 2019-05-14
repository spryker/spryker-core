<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Oauth\Business\OauthFacade;
use Spryker\Zed\OauthCompanyUser\Business\OauthCompanyUserFacade;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    public const ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @return array
     */
    public function indexAction()
    {
        $oAuthCompanyUserFacade = new OauthCompanyUserFacade();

        $token = $oAuthCompanyUserFacade->createCompanyUserAccessToken(
            (new CustomerTransfer())->setCompanyUserTransfer((new CompanyUserTransfer())->setIdCompanyUser(1))
        );

        $companyUserAccessTokenRequestTransfer = (new CompanyUserAccessTokenRequestTransfer())
            ->setAccessToken($token->getAccessToken());

        $customerTransfer = $oAuthCompanyUserFacade->getCustomerByAccessToken($companyUserAccessTokenRequestTransfer);

        dd($customerTransfer);

        $productTable = $this
            ->getFactory()
            ->createProductTable();

        return $this->viewResponse([
            'productTable' => $productTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $productTable = $this
            ->getFactory()
            ->createProductTable();

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }
}
