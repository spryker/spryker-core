<?php
namespace SprykerTest\Zed\SearchElasticsearch;

use Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchBusinessFactory;
use Zend\Filter\Word\UnderscoreToCamelCase;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class SearchElasticsearchZedTester extends \Codeception\Actor
{
    use _generated\SearchElasticsearchZedTesterActions;

    protected const INDEX_MAP_FULLY_QUALIFIED_CLASS_NAME_TEMPLATE = 'Generated\Shared\Search\%sIndexMap';

    protected static $indexDefinitions = [];

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchBusinessFactory
     */
    public function getSearchElasticsearchBusinessFactory(): SearchElasticsearchBusinessFactory
    {
        return new SearchElasticsearchBusinessFactory();
    }

    /**
     * @return string[]
     */
    public function getExpectedIndexNames(): array
    {
        $indexNames = [];

        foreach ($this->getIndexDefinitionTransfers() as $indexDefinitionTransfer) {
            $indexNames[] = $indexDefinitionTransfer->getIndexName();
        }

        return $indexNames;
    }

    /**
     * @return string[]
     */
    public function getExpectedIndexMapClassNames(): array
    {
        $indexMapClassNames = [];

        foreach ($this->getIndexDefinitionTransfers() as $indexDefinitionTransfer) {
            foreach ($indexDefinitionTransfer->getMappings() as $mappingName => $mapping) {
                $classPrefix = $this->normalizeToClassPrefix($mappingName);
                $indexMapClassNames[] = sprintf(static::INDEX_MAP_FULLY_QUALIFIED_CLASS_NAME_TEMPLATE, $classPrefix);
            }
        }

        return $indexMapClassNames;
    }

    /**
     * @return \Generated\Shared\Transfer\IndexDefinitionTransfer[]
     */
    protected function getIndexDefinitionTransfers(): array
    {
        if (!static::$indexDefinitions) {
            static::$indexDefinitions = $this->getSearchElasticsearchBusinessFactory()->createIndexDefinitionBuilder()->build();
        }

        return static::$indexDefinitions;
    }

    /**
     * @param $mappingName
     *
     * @return string
     */
    protected function normalizeToClassPrefix($mappingName): string
    {
        $normalized = preg_replace('/\\W+/', '_', $mappingName);
        $normalized = trim($normalized, '_');

        $filter = new UnderscoreToCamelCase();
        $normalized = $filter->filter($normalized);
        $normalized = ucfirst($normalized);

        return $normalized;
    }
}
