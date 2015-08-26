<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;
use SprykerFeature\Zed\User\Business\UserFacade;
use SprykerFeature\Zed\User\Communication\UserDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;

/**
 * @method UserDependencyContainer getDependencyContainer
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
        $usersTable = $this->getDependencyContainer()->createUserTable();

        return [
            'users' => $usersTable->render()
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createUserTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }
}
