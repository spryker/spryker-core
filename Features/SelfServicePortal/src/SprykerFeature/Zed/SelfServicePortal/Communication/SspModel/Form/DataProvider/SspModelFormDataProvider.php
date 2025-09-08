<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Form\DataProvider;

use Generated\Shared\Transfer\SspModelTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Form\SspModelForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Provider\ModelImageUrlProvider;

class SspModelFormDataProvider
{
    public function __construct(
        protected ModelImageUrlProvider $modelImageUrlProvider
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(SspModelTransfer $sspModelTransfer): array
    {
        return [
            SspModelForm::OPTION_ORIGINAL_IMAGE_URL => $this->getModelImageUrl($sspModelTransfer),
        ];
    }

    protected function getModelImageUrl(SspModelTransfer $sspModelTransfer): ?string
    {
        return $this->modelImageUrlProvider->getImageUrl($sspModelTransfer);
    }
}
