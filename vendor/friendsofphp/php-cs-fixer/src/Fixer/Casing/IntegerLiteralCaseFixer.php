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

namespace PhpCsFixer\Fixer\Casing;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Preg;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

<<<<<<< HEAD
/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
final class IntegerLiteralCaseFixer extends AbstractFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Integer literals must be in correct case.',
            [
                new CodeSample(
                    "<?php\n\$foo = 0Xff;\n\$bar = 0B11111111;\n"
                ),
            ]
        );
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(\T_LNUMBER);
    }

    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(\T_LNUMBER)) {
                continue;
            }

            $content = $token->getContent();

<<<<<<< HEAD
            $newContent = Preg::replaceCallback(
                '#^0([boxBOX])([0-9a-fA-F_]+)$#',
                // @phpstan-ignore-next-line offsetAccess.notFound
                static fn (array $matches): string => '0'.strtolower($matches[1]).strtoupper($matches[2]),
                $content
            );
=======
            $newContent = Preg::replaceCallback('#^0([boxBOX])([0-9a-fA-F_]+)$#', static fn ($matches) => '0'.strtolower($matches[1]).strtoupper($matches[2]), $content);
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d

            if ($content === $newContent) {
                continue;
            }

            $tokens[$index] = new Token([\T_LNUMBER, $newContent]);
        }
    }
}
