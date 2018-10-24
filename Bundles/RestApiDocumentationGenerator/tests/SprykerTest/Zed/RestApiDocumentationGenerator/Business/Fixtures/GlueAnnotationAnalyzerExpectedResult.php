<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business\Fixtures;

class GlueAnnotationAnalyzerExpectedResult
{
    /**
     * @return array
     */
    public static function getTestCreateRestApiDocumentationFromPluginsExpectedResult(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/glue_annotation_analyzer_expected_result.json'), true);
    }
}
