<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 */
class RulesetController extends AbstractController
{
    public const ROLE_UPDATE_URL = '/acl/role/update?id-role=%d';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_DELETE)) {
            throw new MethodNotAllowedHttpException([Request::METHOD_DELETE], 'This action requires a DELETE request.');
        }

        $idRule = $this->castId($request->request->get('id-rule'));
        $idRole = $this->castId($request->request->get('id-role'));

        if (empty($idRule)) {
            $this->addErrorMessage('Missing rule id!');

            return $this->redirectResponse(sprintf(self::ROLE_UPDATE_URL, $idRole));
        }

        $removeStatus = $this->getFacade()->removeRule($idRule);

        if ($removeStatus) {
            $this->addSuccessMessage(sprintf('Rule with id "%d" was successfully removed!', $idRule));
        } else {
            $this->addErrorMessage('Failed to remove rule');
        }

        return $this->redirectResponse(sprintf(self::ROLE_UPDATE_URL, $idRole));
    }
}
