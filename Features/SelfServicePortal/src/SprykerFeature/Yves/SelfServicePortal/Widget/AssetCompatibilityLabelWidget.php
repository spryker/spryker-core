<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
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
    protected const PARAMETER_ID_PRODUCT = 'idProduct';

    public function __construct(string $assetReference, int $idProduct)
    {
        $this->addIsCompatibleParameter($assetReference, $idProduct);
        $this->addAssetReferenceParameter($assetReference);
        $this->addIdProductParameter($idProduct);
    }

    protected function addIsCompatibleParameter(string $assetReference, int $idProduct): void
    {
        $isCompatible = $this->getFactory()
            ->createAssetProductCompatibilityChecker()
            ->isAssetCompatibleToProduct($assetReference, $idProduct);

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

    protected function addIdProductParameter(int $idProduct): void
    {
        $this->addParameter(static::PARAMETER_ID_PRODUCT, $idProduct);
    }
}
