<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Provider;

use Generated\Shared\Transfer\SspModelTransfer;
use Spryker\Service\UtilText\Model\Url\Url;

class ModelImageUrlProvider
{
    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ModelImageController
     *
     * @var string
     */
    protected const ROUTE_NAME_MODEL_VIEW_IMAGE = '/self-service-portal/model-image';

    public function getImageUrl(SspModelTransfer $sspModelTransfer): ?string
    {
        $imageUrl = $sspModelTransfer->getImageUrl();

        if ($sspModelTransfer->getImage()) {
            return Url::generate(static::ROUTE_NAME_MODEL_VIEW_IMAGE, ['ssp-model-reference' => $sspModelTransfer->getReferenceOrFail()])->build();
        }

        return $imageUrl;
    }
}
