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
 * @TODO 4.0 Include in main ConfigInterface
<<<<<<< HEAD
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
 */
interface UnsupportedPhpVersionAllowedConfigInterface extends ConfigInterface
{
    /**
     * Returns true if execution should be allowed on unsupported PHP version whose syntax is not yet supported by the fixer.
     */
    public function getUnsupportedPhpVersionAllowed(): bool;

    public function setUnsupportedPhpVersionAllowed(bool $isUnsupportedPhpVersionAllowed): ConfigInterface;
}
