<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Runner;

use function preg_match;
use function round;
use PHPUnit\Framework\TestStatus\TestStatus;

/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class ResultCacheExtension implements AfterIncompleteTestHook, AfterLastTestHook, AfterRiskyTestHook, AfterSkippedTestHook, AfterSuccessfulTestHook, AfterTestErrorHook, AfterTestFailureHook, AfterTestWarningHook
{
    private TestResultCache $cache;

    public function __construct(TestResultCache $cache)
    {
        $this->cache = $cache;
    }

    public function flush(): void
    {
        $this->cache->persist();
    }

    public function executeAfterSuccessfulTest(string $test, float $time): void
    {
        $testName = $this->getTestName($test);

        $this->cache->setTime($testName, round($time, 3));
    }

    public function executeAfterIncompleteTest(string $test, string $message, float $time): void
    {
        $testName = $this->getTestName($test);

        $this->cache->setTime($testName, round($time, 3));
        $this->cache->setStatus($testName, TestStatus::incomplete($message));
    }

    public function executeAfterRiskyTest(string $test, string $message, float $time): void
    {
        $testName = $this->getTestName($test);

        $this->cache->setTime($testName, round($time, 3));
        $this->cache->setStatus($testName, TestStatus::risky($message));
    }

    public function executeAfterSkippedTest(string $test, string $message, float $time): void
    {
        $testName = $this->getTestName($test);

        $this->cache->setTime($testName, round($time, 3));
        $this->cache->setStatus($testName, TestStatus::skipped($message));
    }

    public function executeAfterTestError(string $test, string $message, float $time): void
    {
        $testName = $this->getTestName($test);

        $this->cache->setTime($testName, round($time, 3));
        $this->cache->setStatus($testName, TestStatus::error($message));
    }

    public function executeAfterTestFailure(string $test, string $message, float $time): void
    {
        $testName = $this->getTestName($test);

        $this->cache->setTime($testName, round($time, 3));
        $this->cache->setStatus($testName, TestStatus::failure($message));
    }

    public function executeAfterTestWarning(string $test, string $message, float $time): void
    {
        $testName = $this->getTestName($test);

        $this->cache->setTime($testName, round($time, 3));
        $this->cache->setStatus($testName, TestStatus::warning($message));
    }

    public function executeAfterLastTest(): void
    {
        $this->flush();
    }

    private function getTestName(string $test): string
    {
        $matches = [];

        if (preg_match('/^(?<name>\S+::\S+)(?:(?<dataname> with data set (?:#\d+|"[^"]+"))\s\()?/', $test, $matches)) {
            $test = $matches['name'] . ($matches['dataname'] ?? '');
        }

        return $test;
    }
}
