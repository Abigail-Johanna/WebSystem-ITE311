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

namespace PhpCsFixer\ConfigurationException;

use PhpCsFixer\Console\Command\FixCommandExitStatusCalculator;

/**
 * Exceptions of this type are thrown on misconfiguration of the Fixer.
 *
 * @internal
 *
 * @final Only internal extending this class is supported
<<<<<<< HEAD
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
 */
class InvalidConfigurationException extends \InvalidArgumentException
{
    public function __construct(string $message, ?int $code = null, ?\Throwable $previous = null)
    {
        parent::__construct(
            $message,
            $code ?? FixCommandExitStatusCalculator::EXIT_STATUS_FLAG_HAS_INVALID_CONFIG,
            $previous
        );
    }
}
