<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Validator\Constraints;

use Symfony\Component\HttpFoundation\File\File as FileObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator as SymfonyFileValidator;

class FileValidator extends SymfonyFileValidator
{
    /**
     * @see {@link https://github.com/symfony/validator/blob/6.3/Constraints/FileValidator.php}
     *
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        parent::validate($value, $constraint);

        if ($value === null || $value === '') {
            return;
        }

        $path = $value instanceof FileObject ? $value->getPathname() : (string)$value;
        $basename = $value instanceof UploadedFile ? $value->getClientOriginalName() : basename($path);
        $mimeTypes = (array)$constraint->mimeTypes;

        if ($constraint->isEmptyTypesValidationEnabled && !$mimeTypes && !$constraint->extensions) {
            $this->context->buildViolation($constraint->emptyTypesMessage)->addViolation();

            return;
        }

        if ($constraint->extensions && !$this->hasContextViolationByCode(File::INVALID_EXTENSION_ERROR)) {
            $mimeTypes = $this->validateExtensions($constraint, $basename, $path);
        }

        if ($mimeTypes && !$this->hasContextViolationByCode(File::INVALID_MIME_TYPE_ERROR)) {
            $this->validateMimeTypes($value, $constraint, $mimeTypes, $basename, $path);
        }
    }

    /**
     * @param \Symfony\Component\Validator\Constraint $constraint
     * @param string $basename
     * @param string $path
     *
     * @return list<string>
     */
    protected function validateExtensions(
        Constraint $constraint,
        string $basename,
        string $path
    ): array {
        $fileExtension = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
        $constraintMimeTypes = (array)$constraint->mimeTypes;
        $normalizedExtensions = [];

        foreach ((array)$constraint->extensions as $extension => $mimeTypes) {
            if (!is_string($extension)) {
                $extension = $mimeTypes;
                $mimeTypes = null;
            }

            $normalizedExtensions[] = $extension;
            if ($fileExtension !== $extension) {
                continue;
            }

            if (!$mimeTypes) {
                $mimeTypes = MimeTypes::getDefault()->getMimeTypes($extension);
            }

            return $constraintMimeTypes ? array_intersect($constraintMimeTypes, $mimeTypes) : (array)$mimeTypes;
        }

        $this->context->buildViolation($constraint->extensionsMessage)
            ->setParameter('{{ file }}', $this->formatValue($path))
            ->setParameter('{{ extension }}', $this->formatValue($fileExtension))
            ->setParameter('{{ extensions }}', $this->formatValues($normalizedExtensions))
            ->setParameter('{{ name }}', $this->formatValue($basename))
            ->setCode(File::INVALID_EXTENSION_ERROR)
            ->addViolation();

        return $constraintMimeTypes;
    }

    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     * @param list<string> $mimeTypes
     * @param string $basename
     * @param string $path
     *
     * @return void
     */
    protected function validateMimeTypes(
        mixed $value,
        Constraint $constraint,
        array $mimeTypes,
        string $basename,
        string $path
    ): void {
        $fileMimeType = $value instanceof FileObject ? $value->getMimeType() : MimeTypes::getDefault()->guessMimeType($path);

        foreach ($mimeTypes as $mimeType) {
            if ($this->isMimeTypeValid($fileMimeType, $mimeType)) {
                return;
            }
        }

        $this->context->buildViolation($constraint->mimeTypesMessage)
            ->setParameter('{{ file }}', $this->formatValue($path))
            ->setParameter('{{ type }}', $this->formatValue($fileMimeType))
            ->setParameter('{{ types }}', $this->formatValues($mimeTypes))
            ->setParameter('{{ name }}', $this->formatValue($basename))
            ->setCode(File::INVALID_MIME_TYPE_ERROR)
            ->addViolation();
    }

    /**
     * @param string|null $fileMimeType
     * @param string $allowedMimeType
     *
     * @return bool
     */
    protected function isMimeTypeValid(?string $fileMimeType, string $allowedMimeType): bool
    {
        if ($allowedMimeType === $fileMimeType) {
            return true;
        }
        $discrete = strstr($allowedMimeType, '/*', true);

        return $discrete && $fileMimeType && strstr($fileMimeType, '/', true) === $discrete;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    protected function hasContextViolationByCode(string $code): bool
    {
        foreach ($this->context->getViolations() as $violation) {
            if ($violation->getCode() === $code) {
                return true;
            }
        }

        return false;
    }
}
