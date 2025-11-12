<?php

// Scribe is a dev dependency, so we need to handle it gracefully in production
// Create dummy classes if Scribe is not installed
if (!class_exists('Knuckles\Scribe\Config\AuthIn')) {
    eval('
        namespace Knuckles\Scribe\Config {
            class AuthIn {
                const BEARER = "bearer";
                const QUERY = "query";
                const BODY = "body";
                const HEADER = "header";
            }
        }
    ');
}

if (!class_exists('Knuckles\Scribe\Config\Defaults')) {
    eval('
        namespace Knuckles\Scribe\Config {
            class Defaults {
                const METADATA_STRATEGIES = [];
                const HEADERS_STRATEGIES = [];
                const URL_PARAMETERS_STRATEGIES = [];
                const QUERY_PARAMETERS_STRATEGIES = [];
                const BODY_PARAMETERS_STRATEGIES = [];
                const RESPONSES_STRATEGIES = [];
                const RESPONSE_FIELDS_STRATEGIES = [];
            }
        }
    ');
}

// Use classes (will use dummy if Scribe not installed)
use Knuckles\Scribe\Config\AuthIn;
use Knuckles\Scribe\Config\Defaults;

return [
    // The HTML <title> for the generated documentation.
    'title' => 'Sekolah Galeri API Documentation',

    // A short description of your API. Will be included in the docs webpage, Postman collection and OpenAPI spec.
    'description' => 'REST API untuk sistem galeri sekolah dengan autentikasi Laravel Sanctum. API ini menyediakan endpoint untuk manajemen kategori, posts, galery, foto, dan profile.',

    // Text to place in the "Introduction" section, right after the `description`. Markdown and HTML are supported.
    'intro_text' => <<<INTRO
        This documentation aims to provide all the information you need to work with our API.

        <aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
        You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>
    INTRO,

    // The base URL displayed in the docs.
    'base_url' => config("app.url"),

    // Routes to include in the docs
    'routes' => [
        [
            'match' => [
                'prefixes' => ['api/*'],
                'domains' => ['*'],
            ],
            'include' => [],
            'exclude' => [],
        ],
    ],

    // The type of documentation output to generate.
    'type' => 'laravel',

    // See https://scribe.knuckles.wtf/laravel/reference/config#theme for supported options
    'theme' => 'default',

    'static' => [
        'output_path' => 'public/docs',
    ],

    'laravel' => [
        // Only add routes in development (Scribe is dev dependency)
        'add_routes' => app()->environment('local', 'testing'),

        'docs_url' => '/docs',
        'assets_directory' => null,
        'middleware' => [],
    ],

    'external' => [
        'html_attributes' => []
    ],

    'try_it_out' => [
        'enabled' => true,
        'base_url' => null,
        'use_csrf' => false,
        'csrf_url' => '/sanctum/csrf-cookie',
    ],

    // How is your API authenticated?
    'auth' => [
        'enabled' => false,
        'default' => false,
        // Use simple string instead of enum to avoid class not found errors
        'in' => 'bearer',
        'name' => 'key',
        'use_value' => env('SCRIBE_AUTH_KEY'),
        'placeholder' => '{YOUR_AUTH_KEY}',
        'extra_info' => 'You can retrieve your token by visiting your dashboard and clicking <b>Generate API token</b>.',
    ],

    'example_languages' => [
        'bash',
        'javascript',
    ],

    'postman' => [
        'enabled' => true,
        'overrides' => [],
    ],

    'openapi' => [
        'enabled' => true,
        'overrides' => [],
        'generators' => [],
    ],

    'groups' => [
        'default' => 'Endpoints',
        'order' => [],
    ],

    'logo' => false,
    'last_updated' => 'Last updated: {date:F j, Y}',

    'examples' => [
        'faker_seed' => 1234,
        'models_source' => ['factoryCreate', 'factoryMake', 'databaseFirst'],
    ],

    // Strategies - use empty arrays if Scribe not available
    'strategies' => class_exists('Knuckles\Scribe\Config\Defaults') && class_exists('Knuckles\Scribe\Extracting\Strategies\StaticData') ? [
        'metadata' => [...\Knuckles\Scribe\Config\Defaults::METADATA_STRATEGIES],
        'headers' => [
            ...\Knuckles\Scribe\Config\Defaults::HEADERS_STRATEGIES,
            \Knuckles\Scribe\Extracting\Strategies\StaticData::withSettings(data: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]),
        ],
        'urlParameters' => [...\Knuckles\Scribe\Config\Defaults::URL_PARAMETERS_STRATEGIES],
        'queryParameters' => [...\Knuckles\Scribe\Config\Defaults::QUERY_PARAMETERS_STRATEGIES],
        'bodyParameters' => [...\Knuckles\Scribe\Config\Defaults::BODY_PARAMETERS_STRATEGIES],
        'responses' => function_exists('Knuckles\Scribe\Config\configureStrategy') 
            ? \Knuckles\Scribe\Config\configureStrategy(
                \Knuckles\Scribe\Config\Defaults::RESPONSES_STRATEGIES,
                \Knuckles\Scribe\Extracting\Strategies\Responses\ResponseCalls::withSettings(
                    only: ['GET *'],
                    config: ['app.debug' => false]
                )
            ) 
            : [],
        'responseFields' => [...\Knuckles\Scribe\Config\Defaults::RESPONSE_FIELDS_STRATEGIES],
    ] : [
        'metadata' => [],
        'headers' => [],
        'urlParameters' => [],
        'queryParameters' => [],
        'bodyParameters' => [],
        'responses' => [],
        'responseFields' => [],
    ],

    'database_connections_to_transact' => [config('database.default')],

    'fractal' => [
        'serializer' => null,
    ],
];
