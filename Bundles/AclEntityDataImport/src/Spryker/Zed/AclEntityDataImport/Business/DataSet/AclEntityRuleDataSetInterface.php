<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataSet;

interface AclEntityRuleDataSetInterface
{
    /**
     * @var string
     */
    public const ACL_ROLE_REFERENCE = 'acl_role_reference';

    /**
     * @var string
     */
    public const ACL_SEGMENT_REFERENCE = 'segment_reference';

    /**
     * @var string
     */
    public const FK_ACL_ROLE = 'fk_acl_role';

    /**
     * @var string
     */
    public const FK_ACL_ENTITY_SEGMENT = 'fk_acl_entity_segment';

    /**
     * @var string
     */
    public const ENTITY = 'entity';

    /**
     * @var string
     */
    public const SCOPE = 'scope';

    /**
     * @var string
     */
    public const PERMISSION_MASK = 'permission_mask';
}
