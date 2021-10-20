<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Acl;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface AclConstants
{
    /**
     * @var string
     */
    public const ACL_DEFAULT_RULES = 'ACL_DEFAULT_RULES';

    /**
     * @var string
     */
    public const ACL_DEFAULT_CREDENTIALS = 'ACL_DEFAULT_CREDENTIALS';

    /**
     * @var string
     */
    public const ACL_USER_RULE_WHITELIST = 'ACL_USER_RULE_WHITELIST';

    /**
     * @var string
     */
    public const VALIDATOR_WILDCARD = '*';

    /**
     * @var string
     */
    public const ACL_SESSION_KEY = 'acl';

    /**
     * @var string
     */
    public const ACL_CREDENTIALS_KEY = 'credentials';

    /**
     * @var string
     */
    public const ACL_DEFAULT_KEY = 'default';

    /**
     * @var string
     */
    public const ACL_DEFAULT_RULES_KEY = 'rules';

    /**
     * @var string
     */
    public const ROOT_GROUP = 'root_group';

    /**
     * @var string
     */
    public const ROOT_ROLE = 'root_role';

    /**
     * @var string
     */
    public const ALLOW = 'allow';
}
