<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile;

interface FormDataNormalizerInterface
{
    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function cleanFormData(array $data): array;

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function normalizeFormData(array $data): array;

    /**
     * @param mixed $value
     *
     * @return array<int>
     */
    public function normalizeFormFieldArray($value): array;

    /**
     * @param array<string, mixed> $data
     *
     * @return array<int, mixed>
     */
    public function flattenToIndexedArray(array $data): array;
}
