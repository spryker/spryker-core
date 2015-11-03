<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Controller;

use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNameExistsException;
use SprykerFeature\Zed\Acl\Business\Exception\RootNodeModificationException;
use SprykerFeature\Zed\Acl\Communication\Form\RulesetForm;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Acl\Communication\AclDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Acl\Communication\Form\RoleForm;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method AclDependencyContainer getDependencyContainer()
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
        $roleTable = $this->getDependencyContainer()->createRoleTable();

        return [
            'roleTable' => $roleTable->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $roleTable = $this->getDependencyContainer()->createRoleTable();

        return $this->jsonResponse(
            $roleTable->fetchData()
        );
    }

    /**
     * @return array|RedirectResponse
     */
    public function createAction()
    {
        $ruleForm = $this->getDependencyContainer()->createRoleForm();
        $ruleForm->handleRequest();

        if ($ruleForm->isValid()) {
            $formData = $ruleForm->getData();

            try {
                $roleTransfer = $this->getFacade()->addRole($formData[RoleForm::NAME]);
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
        $rulesetTable = $this->getDependencyContainer()->createRulesetTable($idRole);

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

        $roleForm = $this->getDependencyContainer()->createRoleForm();
        $this->handleRoleForm($roleForm, $roleTransfer);

        $rulesetForm = $this->getDependencyContainer()->createRulesetForm();
        $this->handleRulesetForm($rulesetForm, $idRole);

        if ($rulesetForm->isSubmitted() && $rulesetForm->isValid()) {
            return $this->redirectResponse(sprintf(self::ROLE_UPDATE_URL, $idRole));
        }

        $rulesetTable = $this->getDependencyContainer()->createRulesetTable($idRole);

        return [
            'roleForm' => $roleForm->createView(),
            'rulesetForm' => $rulesetForm->createView(),
            'rulesetTable' => $rulesetTable->render(),
            'roleTransfer' => $roleTransfer,
        ];
    }

    /**
     * @param RulesetForm $rulesetForm
     * @param int     $idRole
     *
     * @return RulesetForm
     */
    protected function handleRulesetForm(RulesetForm $rulesetForm, $idRole)
    {
        $rulesetForm->handleRequest();
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
     * @param RoleForm     $roleForm
     * @param RoleTransfer $roleTransfer
     */
    protected function handleRoleForm(RoleForm $roleForm, RoleTransfer $roleTransfer)
    {
        $roleForm->handleRequest();
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
