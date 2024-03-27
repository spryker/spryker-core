<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Reader;

use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigRequestTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Spryker\Zed\AclEntity\Business\Filter\AclEntityMetadataConfigFilterInterface;
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
     * @var \Spryker\Zed\AclEntity\Business\Filter\AclEntityMetadataConfigFilterInterface
     */
    protected $aclEntityMetadataConfigFilter;

    /**
     * @param array<\Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityMetadataConfigExpanderPluginInterface> $aclEntityMetadataCollectionExpandPlugins
     * @param \Spryker\Zed\AclEntity\Business\Validator\AclEntityMetadataConfigValidatorInterface $aclEntityMetadataConfigValidator
     * @param \Spryker\Zed\AclEntity\Business\Filter\AclEntityMetadataConfigFilterInterface $aclEntityMetadataConfigFilter
     */
    public function __construct(
        array $aclEntityMetadataCollectionExpandPlugins,
        AclEntityMetadataConfigValidatorInterface $aclEntityMetadataConfigValidator,
        AclEntityMetadataConfigFilterInterface $aclEntityMetadataConfigFilter
    ) {
        $this->aclEntityMetadataCollectionExpandPlugins = $aclEntityMetadataCollectionExpandPlugins;
        $this->aclEntityMetadataConfigValidator = $aclEntityMetadataConfigValidator;
        $this->aclEntityMetadataConfigFilter = $aclEntityMetadataConfigFilter;
    }

    /**
     * @param bool $runValidation
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigRequestTransfer|null $aclEntityMetadataConfigRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function getAclEntityMetadataConfig(
        bool $runValidation = true,
        ?AclEntityMetadataConfigRequestTransfer $aclEntityMetadataConfigRequestTransfer = null
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer = new AclEntityMetadataConfigTransfer();
        $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());

        if ($aclEntityMetadataConfigRequestTransfer !== null) {
            $aclEntityMetadataConfigTransfer->setModelName($aclEntityMetadataConfigRequestTransfer->getModelName());
        }

        foreach ($this->aclEntityMetadataCollectionExpandPlugins as $aclEntityMetadataCollectionExpanderPlugin) {
            $aclEntityMetadataConfigTransfer = $aclEntityMetadataCollectionExpanderPlugin->expand(
                $aclEntityMetadataConfigTransfer,
            );
        }
        if ($runValidation) {
            $aclEntityMetadataConfigTransfer = $this->aclEntityMetadataConfigFilter->filter(
                $aclEntityMetadataConfigTransfer,
            );
            $this->aclEntityMetadataConfigValidator->validate($aclEntityMetadataConfigTransfer);
        }

        return $aclEntityMetadataConfigTransfer;
    }
}
