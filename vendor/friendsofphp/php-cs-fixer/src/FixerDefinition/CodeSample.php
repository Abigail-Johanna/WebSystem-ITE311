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

namespace PhpCsFixer\FixerDefinition;

/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * @readonly
<<<<<<< HEAD
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
 */
final class CodeSample implements CodeSampleInterface
{
    private string $code;

    /**
     * @var null|array<string, mixed>
     */
    private ?array $configuration;

    /**
     * @param null|array<string, mixed> $configuration
     */
    public function __construct(string $code, ?array $configuration = null)
    {
        $this->code = $code;
        $this->configuration = $configuration;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getConfiguration(): ?array
    {
        return $this->configuration;
    }
}
