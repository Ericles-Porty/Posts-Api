<?php

use App\Middlewares\AddJsonResponseHeader;
use DI\ContainerBuilder;
use App\Routes\Routes;
use Logger\Elasticsearch\ElasticsearchLogger;
use Slim\Factory\AppFactory;

$env = file_get_contents(ROOT_PATH . '/.env');
$lines = explode("\n", $env);
foreach ($lines as $line) {
    // Regex key=value
    preg_match("/([^#]+)\=(.*)/", $line, $matches);
    if (isset($matches[2])) {
        putenv(trim($line));
    }
}

$builder = new ContainerBuilder();

$container = $builder->addDefinitions(ROOT_PATH . '/app/Config/definitions.php')
    ->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

$logger = $container->get(ElasticsearchLogger::class);

$errorMiddleware = $app->addErrorMiddleware(true, true, true, $logger);

$errorHandler = $errorMiddleware->getDefaultErrorHandler();

$errorHandler->forceContentType('application/json');

$app->add(new AddJsonResponseHeader);

Routes::loadRoutes($app);

$app->run();
