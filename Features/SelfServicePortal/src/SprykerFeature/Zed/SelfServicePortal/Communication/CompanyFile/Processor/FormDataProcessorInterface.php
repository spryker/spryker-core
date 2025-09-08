<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor;

use Symfony\Component\HttpFoundation\Request;

interface FormDataProcessorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>|null
     */
    public function getFormDataFromRequest(Request $request): ?array;

    public function preprocessRequestData(Request $request): void;
}
