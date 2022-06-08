<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\External;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;

class DocumentationGeneratorOpenApiToInflectorAdapter implements DocumentationGeneratorOpenApiToInflectorInterface
{
    /**
     * @param string $word
     *
     * @return string
     */
    public function classify(string $word): string
    {
        if (class_exists(InflectorFactory::class)) {
            $inflector = InflectorFactory::create()->build();

            return $inflector->classify($word);
        }

        return Inflector::classify($word);
    }

    /**
     * @param string $word
     *
     * @return string
     */
    public function singularize(string $word): string
    {
        if (class_exists(InflectorFactory::class)) {
            $inflector = InflectorFactory::create()->build();

            return $inflector->singularize($word);
        }

        return Inflector::singularize($word);
    }
}
