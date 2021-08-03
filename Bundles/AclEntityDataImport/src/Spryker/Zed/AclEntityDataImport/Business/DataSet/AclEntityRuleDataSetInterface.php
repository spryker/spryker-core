<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataSet;

interface AclEntityRuleDataSetInterface
{
    public const ACL_ROLE_REFERENCE = 'acl_role_reference';
    public const ACL_SEGMENT_REFERENCE = 'segment_reference';
    public const FK_ACL_ROLE = 'fk_acl_role';
    public const FK_ACL_ENTITY_SEGMENT = 'fk_acl_entity_segment';
    public const ENTITY = 'entity';
    public const SCOPE = 'scope';
    public const PERMISSION_MASK = 'permission_mask';
}
