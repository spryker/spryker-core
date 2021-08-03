<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataSet;

interface AclEntitySegmentConnectorDataSetInterface
{
    public const DATA_ENTITY = 'data_entity';
    public const REFERENCE_FIELD = 'reference_field';
    public const ENTITY_REFERENCE = 'entity_reference';
    public const ACL_ENTITY_SEGMENT_REFERENCE = 'acl_entity_segment_reference';
    public const FK_TARGET_ENTITY = 'fk_entity';
    public const FK_ACL_ENTITY_SEGMENT = 'fk_acl_entity_segment';
}
