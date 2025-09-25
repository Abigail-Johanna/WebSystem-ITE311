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

namespace PhpCsFixer\Fixer\Alias;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

<<<<<<< HEAD
/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
final class NoAliasLanguageConstructCallFixer extends AbstractFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Master language constructs shall be used instead of aliases.',
            [
                new CodeSample(
<<<<<<< HEAD
                    <<<'PHP'
                        <?php
                        die;

                        PHP
=======
                    '<?php
die;
'
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
                ),
            ]
        );
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(\T_EXIT);
    }

    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(\T_EXIT)) {
                continue;
            }

            if ('exit' === strtolower($token->getContent())) {
                continue;
            }

            $tokens[$index] = new Token([\T_EXIT, 'exit']);
        }
    }
}
