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
 * @internal This interface is not covered by the backward compatibility promise for PHPUnit
 */
interface IsolatedTestRunner
{
<<<<<<< HEAD
    public function run(TestCase $test, bool $runEntireClass, bool $preserveGlobalState, bool $requiresXdebug): void;
=======
    public function run(TestCase $test, bool $runEntireClass, bool $preserveGlobalState): void;
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
}
