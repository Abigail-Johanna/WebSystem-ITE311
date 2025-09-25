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

namespace PhpCsFixer\Runner\Parallel;

/**
 * Common exception for all the errors related to parallelisation.
 *
 * @author Greg Korba <greg@codito.dev>
 *
 * @internal
<<<<<<< HEAD
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
 */
final class ParallelisationException extends \RuntimeException
{
    public static function forUnknownIdentifier(ProcessIdentifier $identifier): self
    {
        return new self('Unknown process identifier: '.$identifier->toString());
    }
}
