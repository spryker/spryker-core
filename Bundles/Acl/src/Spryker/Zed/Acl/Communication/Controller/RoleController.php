<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Controller;

use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Zed\Acl\Business\Exception\RoleNameExistsException;
use Spryker\Zed\Acl\Business\Exception\RootNodeModificationException;
use Spryker\Zed\Acl\Communication\Form\RulesetForm;
use Spryker\Zed\Acl\Persistence\AclQueryContainer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Acl\Business\AclFacade;
use Spryker\Zed\Acl\Communication\AclCommunicationFactory;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Zed\Acl\Communication\Form\RoleForm;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method AclCommunicationFactory getFactory()
 * @method AclFacade getFacade()
 * @method AclQueryContainer getQueryContainer()
 */
class RoleController extends AbstractController
{

    const ACL_ROLE_LIST_URL = '/acl/role/index';
    const ROLE_UPDATE_URL = '/acl/role/update?id-role=%d';

    /**
     * @return array
     */
    public function indexAction()
    {
        $roleTable = $this->getFactory()->createRoleTable();

        return [
            'roleTable' => $roleTable->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $roleTable = $this->getFactory()->createRoleTable();

        return $this->jsonResponse(
            $roleTable->fetchData()
        );
    }

    /**
     * @return array|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $ruleForm = $this->getFactory()->createRoleForm();
        $ruleForm->handleRequest($request);

        if ($ruleForm->isValid()) {
            $formData = $ruleForm->getData();

            try {
                $roleTransfer = $this->getFacade()->addRole($formData[RoleForm::FIELD_NAME]);
                $this->addSuccessMessage('Role successfully added.');

                return $this->redirectResponse(sprintf(self::ROLE_UPDATE_URL, $roleTransfer->getIdAclRole()));
            } catch (RoleNameExistsException $e) {
                $this->addErrorMessage($e->getMessage());
            } catch (RootNodeModificationException $e) {
                $this->addErrorMessage($e->getMessage());
            }
        }

        return [
            'roleForm' => $ruleForm->createView(),
        ];
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idRole = $request->get('id-role');

        if (empty($idRole)) {
            $this->addErrorMessage('Missing role id!');

            return $this->redirectResponse(self::ACL_ROLE_LIST_URL);
        }

        $groupsHavingThisRole = $this->getQueryContainer()->queryRoleHasGroup($idRole)->count();

        if ($groupsHavingThisRole > 0) {
            $this->addErrorMessage('Not possible to delete, role have groups assigned.');

            return $this->redirectResponse(self::ACL_ROLE_LIST_URL);
        }

        $this->getFacade()->removeRole($idRole);

        $this->addSuccessMessage('Role was successfully removed.');

        return $this->redirectResponse(self::ACL_ROLE_LIST_URL);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function rulesetTableAction(Request $request)
    {
        $idRole = $request->get('id-role');
        $rulesetTable = $this->getFactory()->createRulesetTable($idRole);

        return $this->jsonResponse(
            $rulesetTable->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $idRole = $request->get('id-role');

        if (empty($idRole)) {
            $this->addErrorMessage('Missing role id!');

            return $this->redirectResponse(self::ACL_ROLE_LIST_URL);
        }

        $roleTransfer = $this->getFacade()->getRoleById($idRole);

        $roleForm = $this->getFactory()->createRoleForm();
        $this->handleRoleForm($request, $roleForm, $roleTransfer);

        $rulesetForm = $this->getFactory()->createRulesetForm();
        $this->handleRulesetForm($request, $rulesetForm, $idRole);

        if ($rulesetForm->isSubmitted() && $rulesetForm->isValid()) {
            return $this->redirectResponse(sprintf(self::ROLE_UPDATE_URL, $idRole));
        }

        $rulesetTable = $this->getFactory()->createRulesetTable($idRole);

        return [
            'roleForm' => $roleForm->createView(),
            'rulesetForm' => $rulesetForm->createView(),
            'rulesetTable' => $rulesetTable->render(),
            'roleTransfer' => $roleTransfer,
        ];
    }

    /**
     * @param Request $request
     * @param Form $rulesetForm
     * @param int $idRole
     */
    protected function handleRulesetForm(Request $request, Form $rulesetForm, $idRole)
    {
        $rulesetForm->handleRequest($request);
        if ($rulesetForm->isValid()) {
            $formData = $rulesetForm->getData();

            $ruleTransfer = new RuleTransfer();
            $ruleTransfer->setFkAclRole($idRole);
            $ruleTransfer->fromArray($formData, true);

            $ruleTransfer = $this->getFacade()->addRule($ruleTransfer);

            if ($ruleTransfer->getIdAclRule()) {
                $this->addSuccessMessage('Ruleset successfully added.');
            } else {
                $this->addErrorMessage('Failed to add ruleset.');
            }
        }
    }

    /**
     * @param Request $request
     * @param Form $roleForm
     * @param RoleTransfer $roleTransfer
     */
    protected function handleRoleForm(Request $request, Form $roleForm, RoleTransfer $roleTransfer)
    {
        $roleForm->handleRequest($request);
        if (!$roleForm->isSubmitted()) {
            $roleForm->setData($roleTransfer->toArray());
        }

        if ($roleForm->isValid()) {
            $formData = $roleForm->getData();
            $roleTransfer = new RoleTransfer();
            $roleTransfer->fromArray($formData, true);

            try {
                $this->getFacade()->updateRole($roleTransfer);
                $this->addSuccessMessage('Role successfully updated.');
            } catch (RoleNameExistsException $e) {
                $this->addErrorMessage($e->getMessage());
            } catch (RootNodeModificationException $e) {
                $this->addErrorMessage($e->getMessage());
            }
        }
    }

}
