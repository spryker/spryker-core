<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Acl;

interface AclConstants
{

    const ACL_DEFAULT_RULES = 'ACL_DEFAULT_RULES';
    const ACL_DEFAULT_CREDENTIALS = 'ACL_DEFAULT_CREDENTIALS';
    const ACL_USER_RULE_WHITELIST = 'ACL_USER_RULE_WHITELIST';

    const VALIDATOR_WILDCARD = '*';

    const ACL_SESSION_KEY = 'acl';
    const ACL_CREDENTIALS_KEY = 'credentials';
    const ACL_DEFAULT_KEY = 'default';
    const ACL_DEFAULT_RULES_KEY = 'rules';
    const ROOT_GROUP = 'root_group';
    const ROOT_ROLE = 'root_role';
    const ALLOW = 'allow';

}
