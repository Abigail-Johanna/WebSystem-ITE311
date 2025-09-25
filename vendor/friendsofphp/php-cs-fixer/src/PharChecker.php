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

namespace PhpCsFixer;

/**
 * @internal
<<<<<<< HEAD
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
 */
final class PharChecker implements PharCheckerInterface
{
    public function checkFileValidity(string $filename): ?string
    {
        try {
            $phar = new \Phar($filename);
            // free the variable to unlock the file
            unset($phar);
        } catch (\Exception $e) {
            if (!$e instanceof \UnexpectedValueException && !$e instanceof \PharException) {
                throw $e;
            }

            return 'Failed to create Phar instance. '.$e->getMessage();
        }

        return null;
    }
}
