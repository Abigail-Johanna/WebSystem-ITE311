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

use PhpCsFixer\Runner\RunnerConfig;
use React\EventLoop\LoopInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * @author Greg Korba <greg@codito.dev>
 *
 * @readonly
 *
 * @internal
<<<<<<< HEAD
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class ProcessFactory
{
    public function create(
        LoopInterface $loop,
        InputInterface $input,
=======
 */
final class ProcessFactory
{
    private InputInterface $input;

    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    public function create(
        LoopInterface $loop,
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
        RunnerConfig $runnerConfig,
        ProcessIdentifier $identifier,
        int $serverPort
    ): Process {
<<<<<<< HEAD
        $commandArgs = $this->getCommandArgs($serverPort, $identifier, $input, $runnerConfig);
=======
        $commandArgs = $this->getCommandArgs($serverPort, $identifier, $runnerConfig);
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d

        return new Process(
            implode(' ', $commandArgs),
            $loop,
            $runnerConfig->getParallelConfig()->getProcessTimeout()
        );
    }

    /**
     * @private
     *
<<<<<<< HEAD
     * @return non-empty-list<string>
     */
    public function getCommandArgs(int $serverPort, ProcessIdentifier $identifier, InputInterface $input, RunnerConfig $runnerConfig): array
=======
     * @return list<string>
     */
    public function getCommandArgs(int $serverPort, ProcessIdentifier $identifier, RunnerConfig $runnerConfig): array
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
    {
        $phpBinary = (new PhpExecutableFinder())->find(false);

        if (false === $phpBinary) {
            throw new ParallelisationException('Cannot find PHP executable.');
        }

        $mainScript = realpath(__DIR__.'/../../../php-cs-fixer');
        if (false === $mainScript
            && isset($_SERVER['argv'][0])
            && str_contains($_SERVER['argv'][0], 'php-cs-fixer')
        ) {
            $mainScript = $_SERVER['argv'][0];
        }

        if (!is_file($mainScript)) {
            throw new ParallelisationException('Cannot determine Fixer executable.');
        }

        $commandArgs = [
<<<<<<< HEAD
            ProcessUtils::escapeArgument($phpBinary),
            ProcessUtils::escapeArgument($mainScript),
=======
            escapeshellarg($phpBinary),
            escapeshellarg($mainScript),
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
            'worker',
            '--port',
            (string) $serverPort,
            '--identifier',
<<<<<<< HEAD
            ProcessUtils::escapeArgument($identifier->toString()),
=======
            escapeshellarg($identifier->toString()),
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
        ];

        if ($runnerConfig->isDryRun()) {
            $commandArgs[] = '--dry-run';
        }

<<<<<<< HEAD
        if (filter_var($input->getOption('diff'), \FILTER_VALIDATE_BOOLEAN)) {
            $commandArgs[] = '--diff';
        }

        if (filter_var($input->getOption('stop-on-violation'), \FILTER_VALIDATE_BOOLEAN)) {
=======
        if (filter_var($this->input->getOption('diff'), \FILTER_VALIDATE_BOOLEAN)) {
            $commandArgs[] = '--diff';
        }

        if (filter_var($this->input->getOption('stop-on-violation'), \FILTER_VALIDATE_BOOLEAN)) {
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
            $commandArgs[] = '--stop-on-violation';
        }

        foreach (['allow-risky', 'config', 'rules', 'using-cache', 'cache-file'] as $option) {
<<<<<<< HEAD
            $optionValue = $input->getOption($option);

            if (null !== $optionValue) {
                $commandArgs[] = "--{$option}";
                $commandArgs[] = ProcessUtils::escapeArgument($optionValue);
=======
            $optionValue = $this->input->getOption($option);

            if (null !== $optionValue) {
                $commandArgs[] = "--{$option}";
                $commandArgs[] = escapeshellarg($optionValue);
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
            }
        }

        return $commandArgs;
    }
}
