<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Spryk\Form;

interface FormDataNormalizerInterface
{
    /**
     * @param array $formData
     *
     * @return array
     */
    public function normalizeFormData(array $formData): array;
}
