<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 */
class AssetCompatibilityLabelWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_COMPATIBLE = 'isCompatible';

    /**
     * @var string
     */
    protected const PARAMETER_ASSET_REFERENCE = 'assetReference';

    /**
     * @var string
     */
    protected const PARAMETER_SKU = 'sku';

    public function __construct(string $assetReference, string $sku)
    {
        $this->addIsCompatibleParameter($assetReference, $sku);
        $this->addAssetReferenceParameter($assetReference);
        $this->addSkuParameter($sku);
    }

    protected function addIsCompatibleParameter(string $assetReference, string $sku): void
    {
        $compatibilityMatrix = $this->getClient()
            ->getAssetProductCompatibilityMatrix([$assetReference], [$sku]);

        $isCompatible = $compatibilityMatrix[$assetReference][$sku] ?? false;

        $this->addParameter(static::PARAMETER_IS_COMPATIBLE, $isCompatible);
    }

    public static function getName(): string
    {
        return 'AssetCompatibilityLabelWidget';
    }

    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/asset-compatibility-label/asset-compatibility-label.twig';
    }

    protected function addAssetReferenceParameter(string $assetReference): void
    {
        $this->addParameter(static::PARAMETER_ASSET_REFERENCE, $assetReference);
    }

    protected function addSkuParameter(string $sku): void
    {
        $this->addParameter(static::PARAMETER_SKU, $sku);
    }
}
