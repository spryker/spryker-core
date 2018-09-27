<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Controller;

use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Zed\Acl\Business\Exception\RoleNameExistsException;
use Spryker\Zed\Acl\Business\Exception\RootNodeModificationException;
use Spryker\Zed\Acl\Communication\Form\RoleForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 */
class RoleController extends AbstractController
{
    public const PARAM_ID_ROLE = 'id-role';
    public const ACL_ROLE_LIST_URL = '/acl/role/index';
    public const ROLE_UPDATE_URL = '/acl/role/update?id-role=%d';

    /**
     * @return array
     */
    public function indexAction()
    {
        $roleTable = $this->getFactory()->createRoleTable();

        return $this->viewResponse([
            'roleTable' => $roleTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $roleTable = $this->getFactory()->createRoleTable();

        return $this->jsonResponse(
            $roleTable->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $ruleForm = $this->getFactory()
            ->createRoleForm()
            ->handleRequest($request);

        if ($ruleForm->isSubmitted() && $ruleForm->isValid()) {
            $formData = $ruleForm->getData();

            try {
                $roleTransfer = $this->getFacade()->addRole($formData[RoleForm::FIELD_NAME]);

                $this->addSuccessMessage(
                    sprintf('Role "%s" successfully added.', $formData[RoleForm::FIELD_NAME])
                );

                return $this->redirectResponse(
                    sprintf(self::ROLE_UPDATE_URL, $roleTransfer->getIdAclRole())
                );
            } catch (RoleNameExistsException $e) {
                $this->addErrorMessage($e->getMessage());
            } catch (RootNodeModificationException $e) {
                $this->addErrorMessage($e->getMessage());
            }
        }

        return $this->viewResponse([
            'roleForm' => $ruleForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $idAclRole = $this->castId($request->query->get(self::PARAM_ID_ROLE));

        if (empty($idAclRole)) {
            $this->addErrorMessage('Missing role id!');

            return $this->redirectResponse(self::ACL_ROLE_LIST_URL);
        }

        $dataProvider = $this->getFactory()->createAclRoleFormDataProvider();

        $roleForm = $this->getFactory()
            ->createRoleForm($dataProvider->getData($idAclRole))
            ->handleRequest($request);

        $this->handleRoleForm($request, $roleForm);

        $ruleSetForm = $this->createAndHandleRuleSetForm($request, $idAclRole);
        if ($ruleSetForm->isSubmitted() && $ruleSetForm->isValid()) {
            return $this->redirectResponse(sprintf(self::ROLE_UPDATE_URL, $idAclRole));
        }

        $ruleSetTable = $this->getFactory()->createRulesetTable($idAclRole);

        return [
            'roleForm' => $roleForm->createView(),
            'ruleSetForm' => $ruleSetForm->createView(),
            'ruleSetTable' => $ruleSetTable->render(),
            'roleTransfer' => $this->getFacade()->getRoleById($idAclRole),
        ];
    }

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

        $idRole = $this->castId($request->request->get(self::PARAM_ID_ROLE));

        if (empty($idRole)) {
            $this->addErrorMessage('Missing role id!');

            return $this->redirectResponse(self::ACL_ROLE_LIST_URL);
        }

        $groupsHavingThisRole = $this->getQueryContainer()->queryRoleHasGroup($idRole)->count();
        if ($groupsHavingThisRole > 0) {
            $this->addErrorMessage('Unable to delete because role has groups assigned.');

            return $this->redirectResponse(self::ACL_ROLE_LIST_URL);
        }

        $this->getFacade()->removeRole($idRole);
        $this->addSuccessMessage('Role was successfully removed.');

        return $this->redirectResponse(self::ACL_ROLE_LIST_URL);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ruleSetTableAction(Request $request)
    {
        $idRole = $this->castId($request->get(self::PARAM_ID_ROLE));
        $ruleSetTable = $this->getFactory()->createRulesetTable($idRole);

        return $this->jsonResponse(
            $ruleSetTable->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idAclRole
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createAndHandleRuleSetForm(Request $request, $idAclRole)
    {
        $dataProvider = $this->getFactory()->createAclRuleFormDataProvider();

        $ruleSetForm = $this->getFactory()
            ->createRuleForm(
                $dataProvider->getData($idAclRole),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($ruleSetForm->isSubmitted() && $ruleSetForm->isValid()) {
            $ruleTransfer = new RuleTransfer();
            $ruleTransfer = $ruleTransfer->fromArray($ruleSetForm->getData());

            $ruleTransfer = $this->getFacade()->addRule($ruleTransfer);

            if ($ruleTransfer->getIdAclRule()) {
                $this->addSuccessMessage('Rule successfully added.');
            } else {
                $this->addErrorMessage('Failed to add Rule.');
            }
        }

        return $ruleSetForm;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $roleForm
     *
     * @return void
     */
    protected function handleRoleForm(Request $request, FormInterface $roleForm)
    {
        if ($roleForm->isSubmitted() && $roleForm->isValid()) {
            $formData = $roleForm->getData();

            $roleTransfer = new RoleTransfer();
            $roleTransfer->fromArray($formData);

            try {
                $this->getFacade()->updateRole($roleTransfer);
                $this->addSuccessMessage(
                    sprintf('Role "%s" successfully updated.', $roleTransfer->getName())
                );
            } catch (RoleNameExistsException $e) {
                $this->addErrorMessage($e->getMessage());
            } catch (RootNodeModificationException $e) {
                $this->addErrorMessage($e->getMessage());
            }
        }
    }
}
