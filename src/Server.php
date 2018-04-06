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

use GraphQL\Executor\Promise\Adapter\ReactPromiseAdapter;
use GraphQL\Server\ServerConfig;
use GraphQL\Server\StandardServer;
use GraphQL\Type\Schema;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

final class Server implements RequestHandlerInterface
{
    /**
     * @var FieldResolverProxy
     */
    private $fieldResolverProxy;

    /**
     * @var Schema
     */
    private $schema;

    /**
     * @var ReactPromiseAdapter
     */
    private $reactPromiseAdapter;

    private $debugMode;

    public function __construct(Schema $schema, FieldResolverProxy $fieldResolverProxy, $debugMode = false)
    {
        $this->schema = $schema;
        $this->fieldResolverProxy = $fieldResolverProxy;
        $this->reactPromiseAdapter = new ReactPromiseAdapter();
        $this->debugMode = $debugMode;
    }

    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $config = new ServerConfig();
        $config->setContext($request)
            ->setSchema($this->schema)
            ->setFieldResolver($this->fieldResolverProxy)
            ->setPromiseAdapter($this->reactPromiseAdapter)
            ->setDebug($this->debugMode);
        //@TODO: Enable debugging based on ENV
        //@TODO: Add query validation

        $server = new StandardServer($config);

        $memoryStream = fopen('php://memory', 'w+');

        $promise = $server->processPsrRequest($request, new Response(), new Stream($memoryStream));

        $response = null;

        //@TODO: configure if we are in an async env or not
        $promise->adoptedPromise->done(function (ResponseInterface $diactorosResponse) use (&$response) {
            $response = $diactorosResponse;
        });

        return $response;
    }
}
