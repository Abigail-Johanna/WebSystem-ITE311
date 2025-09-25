<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework;

/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 *
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class IsolatedTestRunnerRegistry
{
    private static ?IsolatedTestRunner $runner = null;

<<<<<<< HEAD
    public static function run(TestCase $test, bool $runEntireClass, bool $preserveGlobalState, bool $requiresXdebug): void
=======
    public static function run(TestCase $test, bool $runEntireClass, bool $preserveGlobalState): void
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
    {
        if (self::$runner === null) {
            self::$runner = new SeparateProcessTestRunner;
        }

<<<<<<< HEAD
        self::$runner->run($test, $runEntireClass, $preserveGlobalState, $requiresXdebug);
=======
        self::$runner->run($test, $runEntireClass, $preserveGlobalState);
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
    }

    public static function set(IsolatedTestRunner $runner): void
    {
        self::$runner = $runner;
    }
}
