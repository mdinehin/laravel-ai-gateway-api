# Laravel AI Gateway API

![Laravel Version](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![Composer Version](https://img.shields.io/badge/Composer-2.x-885630?style=flat-square&logo=composer&logoColor=white)
![PostgreSQL Version](https://img.shields.io/badge/PostgreSQL-18.x-4169E1?style=flat-square&logo=postgresql&logoColor=white)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](LICENSE)

<a href="https://github.com/mdinehin/laravel-ai-gateway-api/actions/workflows/tests.yml"><img src="https://github.com/mdinehin/laravel-ai-gateway-api/workflows/Tests/badge.svg" alt="Tests"></a>
<a href="https://github.com/mdinehin/laravel-ai-gateway-api/actions/workflows/lint.yml"><img src="https://github.com/mdinehin/laravel-ai-gateway-api/workflows/Linter/badge.svg" alt="Linter" /></a>
<a href="https://github.com/mdinehin/laravel-ai-gateway-api/actions/workflows/coding-standards.yml"><img src="https://github.com/mdinehin/laravel-ai-gateway-api/actions/workflows/coding-standards.yml/badge.svg" alt="Coding Standards"></a>

A production-ready RESTful API service built with Laravel 13 and the official Laravel AI SDK. It exposes multi-provider Large Language Model (LLM) capabilities, including OpenAI, Anthropic Claude, and Google Gemini, behind clean, structured endpoints.

The service leverages an expressive, driver-based architecture to deliver fluent prompts, structured outputs, and seamless background job processing for client applications.

## Prerequisites

The following software dependencies are required:

- PHP 8.3+
- [Composer](https://getcomposer.org/download/)
- [PostgreSQL](https://www.postgresql.org/download/)

API credentials and keys:

- [OpenAI API key](https://platform.openai.com/)
- [Gemini API key](https://aistudio.google.com/)

## Installation

1. Clone the repository `git clone https://github.com/mdinehin/laravel-ai-gateway-api.git`
2. Install PHP dependencies `composer install`
3. Set up a local PostgreSQL database named `laravel_ai_gateway_api`
4. Copy the `.env.example` file to `.env` and fill in the database credentials
5. Add the AI LLM provider keys to `.env` `OPENAI_API_KEY` and `GEMINI_API_KEY`
6. Run database migrations `php artisan migrate`
7. Run database seeds `php artisan db:seed`
8. Configure environment settings to prevent runtime errors
    - cURL error: `SSL certificate OpenSSL verify result: unable to get local issuer certificate`
    - Download the official CA bundle from [https://curl.se/docs/caextract.html](https://curl.se/docs/caextract.html)
    - Save `cacert.pem` somewhere stable, e.g., `C:\php\cacert.pem`
    - Run `php --ini` in the terminal to locate the active `php.ini` file
    - Open `php.ini` and set these two lines
        - `curl.cainfo = "C:\php\cacert.pem"`
        - `openssl.cafile = "C:\php\cacert.pem"`
9. Start the local server `php artisan serve`

## Prompting the AI Agent

The AI Agent is accessible through the API endpoints defined in the Swagger documentation and Postman collection.

### API Documentation

Swagger UI: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

Postman collection: [laravel_ai_gateway_api.postman_collection.json](https://github.com/mdinehin/laravel-ai-gateway-api/blob/main/storage/api-docs/laravel_ai_gateway_api.postman_collection.json)

### API Reference

| Endpoint | Description |
| --- | --- |
| `/health-check` | Health check |
| `/verify-ai-sdk` | Verify AI SDK setup |
| `/login` | Authenticate user credentials and issue a token |
| `/user` | Get authenticated user |
| `/base-agent` | Prompt base AI agent |
| `/conversations/start` | Start conversation with an AI agent |
| `/conversations/continue` | Continue conversation with an AI agent |
| `/analysis/sentiment` | AI agent with structured output |
| `/anonymous/simple` | Simple anonymous AI agent |
| `/anonymous/structured` | Structured anonymous AI agent |
| `/configured/creative` | Creative configured AI agent |
| `/configured/precise` | Precise configured AI agent |
| `/tools/time-assistant` | Tool-equipped AI agent |
| `/web/search` | AI agent for searching the web |
| `/web/fetch` | AI agent for fetching web content |
| `/files/process-document-file` | Process document from file |
| `/files/process-document-storage` | Process document from local storage |
| `/files/process-image-file` | Process image from file |
| `/files/process-image-url` | Process image from URL |
| `/images/generate` | Generate image |
| `/audio/generate` | Generate audio (text-to-speech) |
| `/audio/transcribe` | Generate text (transcription) |

For detailed request/response payloads, query parameters, and example bodies, please import the Postman collection.

## Commands

| Command | Description |
| --- | --- |
| `php artisan test` | Run the tests |
| `php artisan migrate:fresh --seed` | Reset the database |
| `composer types:check` | Perform static code analysis |
