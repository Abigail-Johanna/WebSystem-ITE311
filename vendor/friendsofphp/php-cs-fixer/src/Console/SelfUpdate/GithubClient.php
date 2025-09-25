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

namespace PhpCsFixer\Console\SelfUpdate;

/**
 * @readonly
 *
 * @internal
<<<<<<< HEAD
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
 */
final class GithubClient implements GithubClientInterface
{
    private string $url;

    public function __construct(string $url = 'https://api.github.com/repos/PHP-CS-Fixer/PHP-CS-Fixer/tags')
    {
        $this->url = $url;
    }

    public function getTags(): array
    {
        $result = @file_get_contents(
            $this->url,
            false,
            stream_context_create([
                'http' => [
                    'header' => 'User-Agent: PHP-CS-Fixer/PHP-CS-Fixer',
                ],
            ])
        );

        if (false === $result) {
            throw new \RuntimeException(\sprintf('Failed to load tags at "%s".', $this->url));
        }

<<<<<<< HEAD
        try {
            /**
             * @var list<array{
             *     name: string,
             *     zipball_url: string,
             *     tarball_url: string,
             *     commit: array{sha: string, url: string},
             * }>
             */
            $result = json_decode($result, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \RuntimeException(\sprintf(
                'Failed to read response from "%s" as JSON: %s.',
                $this->url,
                $e->getMessage(),
=======
        /**
         * @var list<array{
         *     name: string,
         *     zipball_url: string,
         *     tarball_url: string,
         *     commit: array{sha: string, url: string},
         * }>
         */
        $result = json_decode($result, true);
        if (\JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(\sprintf(
                'Failed to read response from "%s" as JSON: %s.',
                $this->url,
                json_last_error_msg()
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
            ));
        }

        return array_map(
            static fn (array $tagData): string => $tagData['name'],
            $result
        );
    }
}
