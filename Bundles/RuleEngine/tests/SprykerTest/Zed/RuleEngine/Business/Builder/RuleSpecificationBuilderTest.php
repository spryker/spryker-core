<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\Builder;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RuleEngineSpecificationRequestBuilder;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
use Spryker\Zed\RuleEngine\Business\Builder\RuleSpecificationBuilder;
use Spryker\Zed\RuleEngine\Business\Builder\RuleSpecificationBuilderInterface;
use Spryker\Zed\RuleEngine\Business\Comparator\ComparatorCheckerInterface;
use Spryker\Zed\RuleEngine\Business\Resolver\RuleSpecificationProviderResolverInterface;
use Spryker\Zed\RuleEngine\Business\Specification\MetaData\MetaDataProviderInterface;
use Spryker\Zed\RuleEngine\Business\Tokenizer\Tokenizer;
use Spryker\Zed\RuleEngine\Business\Tokenizer\TokenizerInterface;
use Spryker\Zed\RuleEngine\Business\Validator\ClauseValidatorInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface;
use SprykerTest\Zed\RuleEngine\Business\SpecificationProvider\CollectorSpecification\TestCollectorAndSpecification;
use SprykerTest\Zed\RuleEngine\Business\SpecificationProvider\CollectorSpecification\TestCollectorOrSpecification;
use SprykerTest\Zed\RuleEngine\Business\SpecificationProvider\TestCollectorRuleSpecificationProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group RuleEngine
 * @group Business
 * @group Builder
 * @group RuleSpecificationBuilderTest
 * Add your own group annotations below this line
 */
class RuleSpecificationBuilderTest extends Unit
{
    /**
     * @uses \Spryker\Zed\RuleEngine\Business\Comparator\Comparator::LIST_DELIMITER
     *
     * @var string
     */
    protected const LIST_DELIMITER = ';';

    /**
     * @var string
     */
    protected const TEST_FIELD_NAME = 'test-field-name';

    /**
     * @return void
     */
    public function testSpecificationBuildSingleClause(): void
    {
        // Arrange
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestBuilder([
            RuleEngineSpecificationRequestTransfer::QUERY_STRING => 'test-field-name = "123"',
        ]))->withRuleEngineSpecificationProviderRequest()->build();

        // Act
        $ruleSpecification = $this->createRuleSpecificationBuilder()->build($ruleEngineSpecificationRequestTransfer);

        // Assert
        $this->assertInstanceOf(RuleSpecificationInterface::class, $ruleSpecification);
    }

    /**
     * @return void
     */
    public function testSpecificationBuildMultipleWithBooleanAndShouldReturnAndSpec(): void
    {
        // Arrange
        $queryString = 'test-field-name is in "123' . static::LIST_DELIMITER . '321" and test-field-name is in  "321' . static::LIST_DELIMITER . ' 123"';
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestBuilder([
            RuleEngineSpecificationRequestTransfer::QUERY_STRING => $queryString,
        ]))->withRuleEngineSpecificationProviderRequest()->build();

        // Act
        $ruleSpecification = $this->createRuleSpecificationBuilder()->build($ruleEngineSpecificationRequestTransfer);

        // Assert
        $this->assertInstanceOf(TestCollectorAndSpecification::class, $ruleSpecification);
    }

    /**
     * @return void
     */
    public function testSpecificationBuildMultipleWithBooleanOrShouldReturnOrSpec(): void
    {
        // Arrange
        $queryString = 'test-field-name is in "123' . static::LIST_DELIMITER . '321" or test-field-name  is in  "321' . static::LIST_DELIMITER . ' 123"';
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestBuilder([
            RuleEngineSpecificationRequestTransfer::QUERY_STRING => $queryString,
        ]))->withRuleEngineSpecificationProviderRequest()->build();

        // Act
        $ruleSpecification = $this->createRuleSpecificationBuilder()->build($ruleEngineSpecificationRequestTransfer);

        // Assert
        $this->assertInstanceOf(TestCollectorOrSpecification::class, $ruleSpecification);
    }

    /**
     * @return void
     */
    public function testSpecificationBuildMultipleParenthesisWithBooleanAndShouldReturnAndSpecs(): void
    {
        // Arrange
        $queryString = '(test-field-name = "231" or (test-field-name = "1" and  test-field-name = "2")) and test-field-name = "3") ';
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestBuilder([
            RuleEngineSpecificationRequestTransfer::QUERY_STRING => $queryString,
        ]))->withRuleEngineSpecificationProviderRequest()->build();

        // Act
        $ruleSpecification = $this->createRuleSpecificationBuilder()->build($ruleEngineSpecificationRequestTransfer);

        // Assert
        $this->assertInstanceOf(TestCollectorAndSpecification::class, $ruleSpecification);
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Builder\RuleSpecificationBuilderInterface
     */
    protected function createRuleSpecificationBuilder(): RuleSpecificationBuilderInterface
    {
        return new RuleSpecificationBuilder(
            $this->createTokenizer(),
            $this->createSpecificationProviderResolverMock(),
            $this->createComparatorCheckerMock(),
            $this->createClauseValidatorMock(),
            $this->createMetaDataProviderMock(),
        );
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Tokenizer\TokenizerInterface
     */
    protected function createTokenizer(): TokenizerInterface
    {
        return new Tokenizer();
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Resolver\RuleSpecificationProviderResolverInterface
     */
    protected function createSpecificationProviderResolverMock(): RuleSpecificationProviderResolverInterface
    {
        $specificationProviderResolverMock = $this->getMockBuilder(RuleSpecificationProviderResolverInterface::class)->getMock();
        $specificationProviderResolverMock->method('resolveRuleSpecificationProviderPlugin')->willReturn($this->createTestCollectorRuleSpecificationProviderPlugin());

        return $specificationProviderResolverMock;
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\ComparatorCheckerInterface
     */
    protected function createComparatorCheckerMock(): ComparatorCheckerInterface
    {
        $comparatorMock = $this->getMockBuilder(ComparatorCheckerInterface::class)->getMock();
        $comparatorMock->method('getCompoundComparatorExpressions')->willReturn(['is', 'in']);
        $comparatorMock->method('isExistingComparator')
            ->willReturnCallback(function (RuleEngineClauseTransfer $ruleEngineClauseTransfer) {
                return $ruleEngineClauseTransfer->getOperator() === '=' || $ruleEngineClauseTransfer->getOperator() === 'is in';
            });
        $comparatorMock->method('isLogicalComparator')->willReturnCallback(function (string $token) {
            return in_array($token, ['and', 'or'], true);
        });

        return $comparatorMock;
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Validator\ClauseValidatorInterface
     */
    protected function createClauseValidatorMock(): ClauseValidatorInterface
    {
        $clauseValidatorMock = $this->getMockBuilder(ClauseValidatorInterface::class)->getMock();
        $clauseValidatorMock->expects($this->atLeastOnce())->method('validateClause');

        return $clauseValidatorMock;
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Specification\MetaData\MetaDataProviderInterface
     */
    protected function createMetaDataProviderMock(): MetaDataProviderInterface
    {
        $metaDataProviderMock = $this->getMockBuilder(MetaDataProviderInterface::class)->getMock();
        $metaDataProviderMock->method('isFieldAvailable')->willReturnCallback(function (array $rulePlugins, string $field) {
            foreach ($rulePlugins as $rulePlugin) {
                if ($rulePlugin->getFieldName() === $field) {
                    return true;
                }
            }

            return false;
        });

        return $metaDataProviderMock;
    }

    /**
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface
     */
    protected function createTestCollectorRuleSpecificationProviderPlugin(): RuleSpecificationProviderPluginInterface
    {
        return new TestCollectorRuleSpecificationProviderPlugin($this->createCollectorRulePluginMock());
    }

    /**
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface
     */
    protected function createCollectorRulePluginMock(): CollectorRulePluginInterface
    {
        $collectorRulePluginMock = $this->getMockBuilder(CollectorRulePluginInterface::class)->getMock();
        $collectorRulePluginMock->method('collect')->willReturn([]);
        $collectorRulePluginMock->method('getFieldName')->willReturn(static::TEST_FIELD_NAME);
        $collectorRulePluginMock->method('acceptedDataTypes')->willReturn(['string', 'numeric', 'list']);

        return $collectorRulePluginMock;
    }
}
