--TEST--
The right events are emitted in the right order for a successful test that targets a trait with #[UsesMethod]
--SKIPIF--
<?php declare(strict_types=1);
require __DIR__ . '/../../_files/skip-if-requires-code-coverage-driver.php';
--FILE--
<?php declare(strict_types=1);
$traceFile    = tempnam(sys_get_temp_dir(), __FILE__);
$coverageFile = tempnam(sys_get_temp_dir(), __FILE__);

$_SERVER['argv'][] = '--do-not-cache-result';
$_SERVER['argv'][] = '--no-configuration';
$_SERVER['argv'][] = '--no-output';
$_SERVER['argv'][] = '--log-events-text';
$_SERVER['argv'][] = $traceFile;
$_SERVER['argv'][] = '--coverage-text=' . $coverageFile;
$_SERVER['argv'][] = '--coverage-filter';
$_SERVER['argv'][] = __DIR__ . '/../_files';
$_SERVER['argv'][] = __DIR__ . '/../_files/TraitTargetedWithUsesMethodTest.php';

require __DIR__ . '/../../bootstrap.php';

(new PHPUnit\TextUI\Application)->run($_SERVER['argv']);

print file_get_contents($traceFile);

unlink($traceFile);
unlink($coverageFile);
--EXPECTF--
PHPUnit Started (PHPUnit %s using %s)
Test Runner Configured
Event Facade Sealed
Test Suite Loaded (1 test)
Test Runner Started
Test Suite Sorted
Test Runner Execution Started (1 test)
Test Suite Started (PHPUnit\DeprecatedAnnotationsTestFixture\TraitTargetedWithUsesMethodTest, 1 test)
Test Preparation Started (PHPUnit\DeprecatedAnnotationsTestFixture\TraitTargetedWithUsesMethodTest::testSomething)
Test Prepared (PHPUnit\DeprecatedAnnotationsTestFixture\TraitTargetedWithUsesMethodTest::testSomething)
Test Passed (PHPUnit\DeprecatedAnnotationsTestFixture\TraitTargetedWithUsesMethodTest::testSomething)
Test Runner Triggered Deprecation (Targeting a trait such as PHPUnit\TestFixture\CoveredTrait with #[UsesMethod] is deprecated.)
Test Finished (PHPUnit\DeprecatedAnnotationsTestFixture\TraitTargetedWithUsesMethodTest::testSomething)
Test Suite Finished (PHPUnit\DeprecatedAnnotationsTestFixture\TraitTargetedWithUsesMethodTest, 1 test)
Test Runner Execution Finished
Test Runner Finished
PHPUnit Finished (Shell Exit Code: 0)