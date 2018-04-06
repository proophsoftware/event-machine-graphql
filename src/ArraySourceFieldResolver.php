<?php
/**
 * This file is part of the proophsoftware/event-machine-graphql.
 * (c) 2017-2018 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\EventMachine\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use Psr\Http\Message\ServerRequestInterface;
use React\Promise\FulfilledPromise;
use React\Promise\PromiseInterface;

final class ArraySourceFieldResolver implements FieldResolver
{
    public function canResolve($source, array $args, ServerRequestInterface $context, ResolveInfo $info): bool
    {
        $source = (array) $source;

        if (is_array($source) && array_key_exists($info->fieldName, $source)) {
            return true;
        }

        return false;
    }

    public function resolve($source, array $args, ServerRequestInterface $context, ResolveInfo $info): PromiseInterface
    {
        $source = (array) $source;

        return new FulfilledPromise($source[$info->fieldName] ?? null);
    }
}
