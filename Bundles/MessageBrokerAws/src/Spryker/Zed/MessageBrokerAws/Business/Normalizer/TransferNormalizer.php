<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

/**
 * @codingStandardsIgnoreFile
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Normalizer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer as SymfonyObjectNormalizer;

if (!interface_exists(CacheableSupportsMethodInterface::class)) {
    // Symfony4 support
    class TransferNormalizer extends SymfonyObjectNormalizer
    {
        /**
         * @param mixed $data
         * @param string $class
         * @param string|null $format
         * @param array<string, mixed> $context
         *
         * @return object
         */
        public function denormalize($data, $class, $format = null, array $context = [])
        {
            if (is_subclass_of($class, AbstractTransfer::class)) {
                return (new $class())->fromArray($data, true);
            }

            return parent::denormalize($data, $class, $format, $context);
        }
    }
} else {
    class TransferNormalizer extends SymfonyObjectNormalizer
    {
        /**
         * @param mixed $data
         * @param string $type
         * @param string|null $format
         * @param array<string, mixed> $context
         *
         * @return object|null
         *
         * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
         */
        public function denormalize($data, string $type, string $format = null, array $context = [])
        {
            if (is_subclass_of($type, AbstractTransfer::class)) {
                return (new $type())->fromArray($data, true);
            }

            return parent::denormalize($data, $type, $format, $context);
        }
    }
}
