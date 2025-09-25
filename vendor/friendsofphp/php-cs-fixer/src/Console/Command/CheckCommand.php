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

namespace PhpCsFixer\Console\Command;

<<<<<<< HEAD
use PhpCsFixer\Preg;
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
use PhpCsFixer\ToolInfoInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Greg Korba <greg@codito.dev>
 *
 * @internal
<<<<<<< HEAD
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
 */
#[AsCommand(name: 'check', description: 'Checks if configured files/directories comply with configured rules.')]
final class CheckCommand extends FixCommand
{
    /** @TODO PHP 8.0 - remove the property */
    protected static $defaultName = 'check'; // @phpstan-ignore property.parentPropertyFinalByPhpDoc

    /** @TODO PHP 8.0 - remove the property */
    protected static $defaultDescription = 'Checks if configured files/directories comply with configured rules.'; // @phpstan-ignore property.parentPropertyFinalByPhpDoc

    public function __construct(ToolInfoInterface $toolInfo)
    {
        parent::__construct($toolInfo);
    }

    public function getHelp(): string
    {
<<<<<<< HEAD
        return Preg::replace('@\v\V*<comment>--dry-run</comment>\V*\v@', '', parent::getHelp());
=======
        $help = explode('<comment>--dry-run</comment>', parent::getHelp());

        return substr($help[0], 0, strrpos($help[0], "\n") - 1)
            .substr($help[1], strpos($help[1], "\n"));
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setDefinition([
            ...array_values($this->getDefinition()->getArguments()),
            ...array_values(array_filter(
                $this->getDefinition()->getOptions(),
                static fn (InputOption $option): bool => 'dry-run' !== $option->getName()
            )),
        ]);
    }

    protected function isDryRun(InputInterface $input): bool
    {
        return true;
    }
}
