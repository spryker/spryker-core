<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclDataImport\Business\DataSet;

interface AclGroupRoleDataImportInterface
{
    /**
     * @var string
     */
    public const ACL_ROLE_REFERENCE = 'role_reference';
    /**
     * @var string
     */
    public const ACL_GROUP_REFERENCE = 'group_reference';
    /**
     * @var string
     */
    public const FK_ACL_GROUP = 'fk_acl_group';
    /**
     * @var string
     */
    public const FK_ACL_ROLE = 'fk_acl_role';
}
