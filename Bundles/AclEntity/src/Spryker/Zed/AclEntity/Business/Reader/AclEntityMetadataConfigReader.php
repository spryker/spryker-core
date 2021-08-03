<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Reader;

use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;

class AclEntityMetadataConfigReader implements AclEntityMetadataConfigReaderInterface
{
    /**
     * @var \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityMetadataConfigExpanderPluginInterface[]
     */
    protected $aclEntityMetadataCollectionExpandPlugins;

    /**
     * @param \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityMetadataConfigExpanderPluginInterface[] $aclEntityMetadataCollectionExpandPlugins
     */
    public function __construct(array $aclEntityMetadataCollectionExpandPlugins)
    {
        $this->aclEntityMetadataCollectionExpandPlugins = $aclEntityMetadataCollectionExpandPlugins;
    }

    /**
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function getAclEntityMetadataConfig(): AclEntityMetadataConfigTransfer
    {
        $aclEntityMetadataConfigTransfer = new AclEntityMetadataConfigTransfer();
        $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());
        foreach ($this->aclEntityMetadataCollectionExpandPlugins as $aclEntityMetadataCollectionExpanderPlugin) {
            $aclEntityMetadataConfigTransfer = $aclEntityMetadataCollectionExpanderPlugin->expand(
                $aclEntityMetadataConfigTransfer
            );
        }

        return $aclEntityMetadataConfigTransfer;
    }
}
