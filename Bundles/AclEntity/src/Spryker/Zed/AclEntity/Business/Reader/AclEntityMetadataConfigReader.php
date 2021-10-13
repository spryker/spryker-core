<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Reader;

use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Spryker\Zed\AclEntity\Business\Validator\AclEntityMetadataConfigValidatorInterface;

class AclEntityMetadataConfigReader implements AclEntityMetadataConfigReaderInterface
{
    /**
     * @var array<\Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityMetadataConfigExpanderPluginInterface>
     */
    protected $aclEntityMetadataCollectionExpandPlugins;

    /**
     * @var \Spryker\Zed\AclEntity\Business\Validator\AclEntityMetadataConfigValidatorInterface
     */
    protected $aclEntityMetadataConfigValidator;

    /**
     * @param array<\Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityMetadataConfigExpanderPluginInterface> $aclEntityMetadataCollectionExpandPlugins
     * @param \Spryker\Zed\AclEntity\Business\Validator\AclEntityMetadataConfigValidatorInterface $aclEntityMetadataConfigValidator
     */
    public function __construct(
        array $aclEntityMetadataCollectionExpandPlugins,
        AclEntityMetadataConfigValidatorInterface $aclEntityMetadataConfigValidator
    ) {
        $this->aclEntityMetadataCollectionExpandPlugins = $aclEntityMetadataCollectionExpandPlugins;
        $this->aclEntityMetadataConfigValidator = $aclEntityMetadataConfigValidator;
    }

    /**
     * @param bool $runValidation
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function getAclEntityMetadataConfig(bool $runValidation): AclEntityMetadataConfigTransfer
    {
        $aclEntityMetadataConfigTransfer = new AclEntityMetadataConfigTransfer();
        $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());
        foreach ($this->aclEntityMetadataCollectionExpandPlugins as $aclEntityMetadataCollectionExpanderPlugin) {
            $aclEntityMetadataConfigTransfer = $aclEntityMetadataCollectionExpanderPlugin->expand(
                $aclEntityMetadataConfigTransfer
            );
        }
        if ($runValidation) {
            $this->aclEntityMetadataConfigValidator->validate($aclEntityMetadataConfigTransfer);
        }

        return $aclEntityMetadataConfigTransfer;
    }
}
