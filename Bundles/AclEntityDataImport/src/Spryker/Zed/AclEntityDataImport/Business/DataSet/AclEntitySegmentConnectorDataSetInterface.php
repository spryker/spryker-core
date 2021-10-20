<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataSet;

interface AclEntitySegmentConnectorDataSetInterface
{
    /**
     * @var string
     */
    public const DATA_ENTITY = 'data_entity';

    /**
     * @var string
     */
    public const REFERENCE_FIELD = 'reference_field';

    /**
     * @var string
     */
    public const ENTITY_REFERENCE = 'entity_reference';

    /**
     * @var string
     */
    public const ACL_ENTITY_SEGMENT_REFERENCE = 'acl_entity_segment_reference';

    /**
     * @var string
     */
    public const FK_TARGET_ENTITY = 'fk_entity';

    /**
     * @var string
     */
    public const FK_ACL_ENTITY_SEGMENT = 'fk_acl_entity_segment';
}
