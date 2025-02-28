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
     * @var array<\Generated\Shared\Transfer\AclEntityMetadataConfigTransfer>
     */
    protected static array $cache = [];

    /**
     * @param array<\Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityMetadataConfigExpanderPluginInterface> $aclEntityMetadataCollectionExpandPlugins
     * @param \Spryker\Zed\AclEntity\Business\Validator\AclEntityMetadataConfigValidatorInterface $aclEntityMetadataConfigValidator
     * @param \Spryker\Zed\AclEntity\Business\Filter\AclEntityMetadataConfigFilterInterface $aclEntityMetadataConfigFilter
     */
    public function __construct(
        protected array $aclEntityMetadataCollectionExpandPlugins,
        protected AclEntityMetadataConfigValidatorInterface $aclEntityMetadataConfigValidator,
        protected AclEntityMetadataConfigFilterInterface $aclEntityMetadataConfigFilter
    ) {
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
        if (!$aclEntityMetadataConfigRequestTransfer) {
            return $this->executeAclEntityMetadataConfig($runValidation);
        }

        if (!isset(static::$cache[$aclEntityMetadataConfigRequestTransfer->getModelName()])) {
            static::$cache[$aclEntityMetadataConfigRequestTransfer->getModelName()] = $this->executeAclEntityMetadataConfig($runValidation, $aclEntityMetadataConfigRequestTransfer);
        }

        return static::$cache[$aclEntityMetadataConfigRequestTransfer->getModelName()];
    }

    /**
     * @param bool $runValidation
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigRequestTransfer|null $aclEntityMetadataConfigRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    protected function executeAclEntityMetadataConfig(
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
