<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\User\Communication\UserCommunicationFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spryker\Zed\User\Persistence\UserQueryContainer;

/**
 * @method UserCommunicationFactory getCommunicationFactory
 * @method UserFacade getFacade()
 * @method UserQueryContainer getQueryContainer()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $usersTable = $this->getCommunicationFactory()->createUserTable();

        return [
            'users' => $usersTable->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getCommunicationFactory()->createUserTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

}
