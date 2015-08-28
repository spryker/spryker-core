<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Acl;

use SprykerFeature\Shared\Library\ConfigInterface;

interface AclConfig extends ConfigInterface
{
    const ACL_DEFAULT_RULES = 'ACL_DEFAULT_RULES';
    const ACL_DEFAULT_CREDENTIALS = 'ACL_DEFAULT_CREDENTIALS';
    const ACL_USER_RULE_WHITELIST = 'ACL_USER_RULE_WHITELIST';

}
