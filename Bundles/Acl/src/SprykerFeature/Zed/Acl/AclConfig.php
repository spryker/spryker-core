<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\Acl\AclConfig as AclSharedConfig;
use SprykerFeature\Shared\Library\Config;

class AclConfig extends AbstractBundleConfig
{

    const VALIDATOR_WILDCARD = '*';

    const ACL_SESSION_KEY = 'acl';
    const ACL_CREDENTIALS_KEY = 'credentials';
    const ACL_DEFAULT_KEY = 'default';
    const ACL_DEFAULT_RULES_KEY = 'rules';
    const ROOT_GROUP = 'root_group';
    const ROOT_ROLE = 'root_role';
    const ALLOW = 'allow';

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
        $default = Config::get(AclSharedConfig::ACL_DEFAULT_RULES);

        return array_merge($default, $this->rules);
    }

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param string $type
     */
    public function setRules($bundle, $controller, $action, $type)
    {
        $this->rules[] = [
            'bundle'     => $bundle,
            'controller' => $controller,
            'action'     => $action,
            'type'       => $type,
        ];
    }

    /**
     * @return array
     */
    public function getCredentials()
    {
        return Config::get(AclSharedConfig::ACL_DEFAULT_CREDENTIALS);
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
                'bundle'     => self::VALIDATOR_WILDCARD,
                'controller' => self::VALIDATOR_WILDCARD,
                'action'     => self::VALIDATOR_WILDCARD,
                'type'       => self::ALLOW,
                'role'       => self::ROOT_ROLE,
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
                'name'  => self::ROOT_ROLE,
                'group' => self::ROOT_GROUP,
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
                'name' => self::ROOT_GROUP,
            ],
        ];
    }

    public function getInstallerUsers()
    {
        return [
            'admin@spryker.com' => [
                'group' => self::ROOT_GROUP,
            ],
            //this is related to existent username and will be searched into the database
        ];
    }

    /**
     * @return array
     */
    public function getUserRuleWhitelist()
    {
        if (Config::hasValue(AclSharedConfig::ACL_USER_RULE_WHITELIST)) {
            return Config::get(AclSharedConfig::ACL_USER_RULE_WHITELIST);
        }
        return [];
    }
}
