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

use GraphQL\Language\AST\TypeDefinitionNode;

final class TypeConfigDecoratorProxy
{
    /**
     * @var TypeConfigDecorator
     */
    private $decorator;

    public function __construct(TypeConfigDecorator $decorator)
    {
        $this->decorator = $decorator;
    }

    public function __invoke(array $typeConfig, TypeDefinitionNode $node): array
    {
        return $this->decorator->decorate($typeConfig, $node);
    }
}
