<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\Linter;

/**
 * Interface for PHP code linting process manager.
 *
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
<<<<<<< HEAD
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
 */
interface LinterInterface
{
    public function isAsync(): bool;

    /**
     * Lint PHP file.
     */
    public function lintFile(string $path): LintingResultInterface;

    /**
     * Lint PHP code.
     */
    public function lintSource(string $source): LintingResultInterface;
}
