<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl;

use Generated\Zed\Ide\AutoCompletion;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Shared\Acl\AclConstants;
use Spryker\Shared\Config;

class AclConfig extends AbstractBundleConfig
{

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @return array
     */
    public function getRules()
    {
        $default = Config::get(AclConstants::ACL_DEFAULT_RULES);

        return array_merge($default, $this->rules);
    }

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param string $type
     *
     * @return void
     */
    public function setRules($bundle, $controller, $action, $type)
    {
        $this->rules[] = [
            'bundle' => $bundle,
            'controller' => $controller,
            'action' => $action,
            'type' => $type,
        ];
    }

    /**
     * @return array
     */
    public function getCredentials()
    {
        return Config::get(AclConstants::ACL_DEFAULT_CREDENTIALS);
    }

    /**
     * @return string
     */
    public function getAccessDeniedUri()
    {
        return '/acl/index/denied';
    }

    /**
     * @return array
     */
    public function getInstallerRules()
    {
        return [
            [
                'bundle' => AclConstants::VALIDATOR_WILDCARD,
                'controller' => AclConstants::VALIDATOR_WILDCARD,
                'action' => AclConstants::VALIDATOR_WILDCARD,
                'type' => AclConstants::ALLOW,
                'role' => AclConstants::ROOT_ROLE,
                //this is related to the installer_data only and will not interact with existing data if any
            ],
        ];
    }

    /**
     * @return array
     */
    public function getInstallerRoles()
    {
        return [
            [
                'name' => AclConstants::ROOT_ROLE,
                'group' => AclConstants::ROOT_GROUP,
                //this is related to the installer_data only and will not interact with existing data if any
            ],
        ];
    }

    /**
     * @return array
     */
    public function getInstallerGroups()
    {
        return [
            [
                'name' => AclConstants::ROOT_GROUP,
            ],
        ];
    }

    public function getInstallerUsers()
    {
        return [
            'admin@spryker.com' => [
                'group' => AclConstants::ROOT_GROUP,
            ],
            //this is related to existent username and will be searched into the database
        ];
    }

    /**
     * @return array
     */
    public function getUserRuleWhitelist()
    {
        if (Config::hasValue(AclConstants::ACL_USER_RULE_WHITELIST)) {
            return Config::get(AclConstants::ACL_USER_RULE_WHITELIST);
        }

        return [];
    }

}
