<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\PageMapTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilder;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Search
 * @group Business
 * @group Model
 * @group Elasticsearch
 * @group DataMapper
 * @group PageMapBuilderTest
 */
class PageMapBuilderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilder
     */
    protected $pageMapBuilder;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->pageMapBuilder = new PageMapBuilder();
    }

    /**
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testAddingInvalidFieldShouldThrowException()
    {
        $pageMapTransfer = new PageMapTransfer();
        $this->pageMapBuilder->add($pageMapTransfer, 'non-existing-field', 'foo', 'bar');
    }

    /**
     * @dataProvider pageMapTransferDataProvider
     *
     * @param string $field
     * @param string $attributeName
     * @param mixed $attributeValue
     * @param array $expectedResult
     *
     * @return void
     */
    public function testAddingDataToPageMapTransferIsExtendingItInTheirExpectedFormat($field, $attributeName, $attributeValue, array $expectedResult)
    {
        $pageMapTransfer = new PageMapTransfer();
        $this->pageMapBuilder->add($pageMapTransfer, $field, $attributeName, $attributeValue);

        $this->assertSame($expectedResult, $pageMapTransfer->modifiedToArray());
    }

    /**
     * @return array
     */
    public function pageMapTransferDataProvider()
    {
        return [
            'single fulltext' => $this->createSingleFulltextData(),
            'multiple fulltext' => $this->createMultipleFulltextData(),
            'single fulltext boosted' => $this->createSingleFulltextBoostedData(),
            'multiple fulltext boosted' => $this->createMultipleFulltextBoostedData(),
            'single completion terms' => $this->createSingleCompletionTermsData(),
            'multiple completion terms' => $this->createMultipleCompletionTermsData(),
            'single suggestion terms' => $this->createSingleSuggestionTermsData(),
            'multiple suggestion terms' => $this->createMultipleSuggestionTermsData(),
            'simple search result data' => $this->createSimpleSearchResultData(),
            'array search result data' => $this->createArraySearchResultData(),
            'single string facet' => $this->createSingleStringFacetData(),
            'multiple string facet' => $this->createMultipleStringFacetData(),
            'single integer facet' => $this->createSingleIntegerFacetData(),
            'multiple integer facet' => $this->createMultipleIntegerFacetData(),
            'string sort' => $this->createStringSortData(),
            'integer sort' => $this->createIntegerSortData(),
        ];
    }

    /**
     * @return array
     */
    protected function createSingleFulltextData()
    {
        $field = PageIndexMap::FULL_TEXT;
        $attributeName = 'foo-name';
        $attributeValue = 'foo-value';

        $expectedResult = [
            'full_text' => [
                'foo-value',
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createMultipleFulltextData()
    {
        $field = PageIndexMap::FULL_TEXT;
        $attributeName = 'foo-name';
        $attributeValue = ['foo-value-1', 'foo-value-2', 'foo-value-3'];

        $expectedResult = [
            'full_text' => [
                'foo-value-1',
                'foo-value-2',
                'foo-value-3',
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createSingleFulltextBoostedData()
    {
        $field = PageIndexMap::FULL_TEXT_BOOSTED;
        $attributeName = 'foo-name';
        $attributeValue = 'foo-value';

        $expectedResult = [
            'full_text_boosted' => [
                'foo-value',
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createMultipleFulltextBoostedData()
    {
        $field = PageIndexMap::FULL_TEXT_BOOSTED;
        $attributeName = 'foo-name';
        $attributeValue = ['foo-value-1', 'foo-value-2', 'foo-value-3'];

        $expectedResult = [
            'full_text_boosted' => [
                'foo-value-1',
                'foo-value-2',
                'foo-value-3',
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createSingleCompletionTermsData()
    {
        $field = PageIndexMap::COMPLETION_TERMS;
        $attributeName = 'foo-name';
        $attributeValue = 'foo-value';

        $expectedResult = [
            'completion_terms' => [
                'foo-value',
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createMultipleCompletionTermsData()
    {
        $field = PageIndexMap::COMPLETION_TERMS;
        $attributeName = 'foo-name';
        $attributeValue = ['foo-value-1', 'foo-value-2', 'foo-value-3'];

        $expectedResult = [
            'completion_terms' => [
                'foo-value-1',
                'foo-value-2',
                'foo-value-3',
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createSingleSuggestionTermsData()
    {
        $field = PageIndexMap::SUGGESTION_TERMS;
        $attributeName = 'foo-name';
        $attributeValue = 'foo-value';

        $expectedResult = [
            'suggestion_terms' => [
                'foo-value',
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createMultipleSuggestionTermsData()
    {
        $field = PageIndexMap::SUGGESTION_TERMS;
        $attributeName = 'foo-name';
        $attributeValue = ['foo-value-1', 'foo-value-2', 'foo-value-3'];

        $expectedResult = [
            'suggestion_terms' => [
                'foo-value-1',
                'foo-value-2',
                'foo-value-3',
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createSimpleSearchResultData()
    {
        $field = PageIndexMap::SEARCH_RESULT_DATA;
        $attributeName = 'foo-name';
        $attributeValue = 'foo-value';

        $expectedResult = [
            'search_result_data' => [
                [
                    'name' => 'foo-name',
                    'value' => 'foo-value',
                ],
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createArraySearchResultData()
    {
        $field = PageIndexMap::SEARCH_RESULT_DATA;
        $attributeName = 'foo-name';
        $attributeValue = ['foo-value-1', 'foo-value-2', 'foo-value-3'];

        $expectedResult = [
            'search_result_data' => [
                [
                    'name' => 'foo-name',
                    'value' => [
                        'foo-value-1',
                        'foo-value-2',
                        'foo-value-3',
                    ],
                ],
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createSingleStringFacetData()
    {
        $field = PageIndexMap::STRING_FACET;
        $attributeName = 'foo-name';
        $attributeValue = 'foo-value';

        $expectedResult = [
            'string_facet' => [
                [
                    'name' => 'foo-name',
                    'value' => [
                        'foo-value',
                    ],
                ],
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createMultipleStringFacetData()
    {
        $field = PageIndexMap::STRING_FACET;
        $attributeName = 'foo-name';
        $attributeValue = ['foo-value-1', 'foo-value-2', 'foo-value-3'];

        $expectedResult = [
            'string_facet' => [
                [
                    'name' => 'foo-name',
                    'value' => [
                        'foo-value-1',
                        'foo-value-2',
                        'foo-value-3',
                    ],
                ],
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createSingleIntegerFacetData()
    {
        $field = PageIndexMap::INTEGER_FACET;
        $attributeName = 'foo-name';
        $attributeValue = '1';

        $expectedResult = [
            'integer_facet' => [
                [
                    'name' => 'foo-name',
                    'value' => [
                        1,
                    ],
                ],
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createMultipleIntegerFacetData()
    {
        $field = PageIndexMap::INTEGER_FACET;
        $attributeName = 'foo-name';
        $attributeValue = ['1', 2, 3.0];

        $expectedResult = [
            'integer_facet' => [
                [
                    'name' => 'foo-name',
                    'value' => [
                        1,
                        2,
                        3,
                    ],
                ],
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createStringSortData()
    {
        $field = PageIndexMap::STRING_SORT;
        $attributeName = 'foo-name';
        $attributeValue = 'foo-value';

        $expectedResult = [
            'string_sort' => [
                [
                    'name' => 'foo-name',
                    'value' => 'foo-value',
                ],
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

    /**
     * @return array
     */
    protected function createIntegerSortData()
    {
        $field = PageIndexMap::INTEGER_SORT;
        $attributeName = 'foo-name';
        $attributeValue = '1';

        $expectedResult = [
            'integer_sort' => [
                [
                    'name' => 'foo-name',
                    'value' => 1,
                ],
            ],
        ];

        return [$field, $attributeName, $attributeValue, $expectedResult];
    }

}
