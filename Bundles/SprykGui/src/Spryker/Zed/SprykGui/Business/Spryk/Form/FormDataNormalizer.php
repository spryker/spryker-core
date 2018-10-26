<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Spryk\Form;

use Generated\Shared\Transfer\ArgumentCollectionTransfer;
use Generated\Shared\Transfer\ArgumentTransfer;
use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\ReturnTypeTransfer;

class FormDataNormalizer implements FormDataNormalizerInterface
{
    /**
     * @param array $formData
     *
     * @return array
     */
    public function normalizeFormData(array $formData): array
    {
        return $this->normalizeFormDataRecursive($formData, []);
    }

    /**
     * @param array $data
     * @param array $normalizedData
     *
     * @return array
     */
    protected function normalizeFormDataRecursive(array $data, array $normalizedData): array
    {
        foreach ($data as $key => $value) {
            if ($key === 'spryk' || isset($normalizedData[$key])) {
                continue;
            }

            if ($key === 'sprykDetails') {
                $normalizedData = $this->normalizeFormDataRecursive($value, $normalizedData);

                continue;
            }

            if ($value instanceof ModuleTransfer) {
                $normalizedData['module'] = $value->getName();
                $normalizedData['organization'] = $value->getOrganization()->getName();
                $normalizedData['rootPath'] = $value->getOrganization()->getRootPath();

                continue;
            }

            if ($value instanceof ReturnTypeTransfer) {
                $value = $value->getType();
            }

            if ($value instanceof ClassInformationTransfer) {
                $value = $value->getFullyQualifiedClassName();
            }

            if ($value instanceof ArgumentCollectionTransfer) {
                $normalizedData = $this->normalizeArgumentCollection($key, $value, $normalizedData);

                continue;
            }

            $normalizedData[$key] = $value;
        }

        return $normalizedData;
    }

    /**
     * @param string $argumentName
     * @param \Generated\Shared\Transfer\ArgumentCollectionTransfer $argumentCollectionTransfer
     * @param array $normalizedData
     *
     * @return array
     */
    protected function normalizeArgumentCollection(string $argumentName, ArgumentCollectionTransfer $argumentCollectionTransfer, array $normalizedData)
    {
        $arguments = [];
        $methods = [];

        foreach ($argumentCollectionTransfer->getArguments() as $argumentTransfer) {
            $arguments[] = $this->buildFromArgument($argumentTransfer);
            if ($argumentTransfer->getArgumentMeta() && $argumentTransfer->getArgumentMeta()->getMethod()) {
                $methods[] = $argumentTransfer->getArgumentMeta()->getMethod();
            }
        }

        $normalizedData[$argumentName] = $arguments;
        $normalizedData['dependencyMethods'] = $methods;

        return $normalizedData;
    }

    /**
     * @param \Generated\Shared\Transfer\ArgumentTransfer $argumentTransfer
     *
     * @return string
     */
    protected function buildFromArgument(ArgumentTransfer $argumentTransfer)
    {
        $pattern = '%s %s';
        if ($argumentTransfer->getIsOptional()) {
            $pattern = '?%s %s';
        }
        if ($argumentTransfer->getDefaultValue()) {
            $pattern .= sprintf(' = %s', $argumentTransfer->getDefaultValue());
        }

        return sprintf($pattern, $argumentTransfer->getType(), $argumentTransfer->getVariable());
    }
}
