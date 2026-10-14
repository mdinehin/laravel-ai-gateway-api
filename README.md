# Laravel AI Software Development Kit (SDK)

<a href="https://github.com/mdinehin/laravel-ai-sdk/actions/workflows/tests.yml">
    <img src="https://github.com/mdinehin/laravel-ai-sdk/workflows/tests/badge.svg" alt="Tests">
</a>
<a href="https://github.com/mdinehin/laravel-ai-sdk/actions/workflows/lint.yml">
    <img src="https://github.com/mdinehin/laravel-ai-sdk/workflows/linter/badge.svg" alt="Linter" />
</a>

The source code for my personal AI SDK.

## Requirements

The installation process requires the following tools.

- PHP 8.4
- Composer
- PostgreSQL
- [OpenAI API key](https://platform.openai.com/)
- [Gemini API key](https://aistudio.google.com/)

## Installation

1. Clone this repository locally with `git clone https://github.com/mdinehin/laravel-ai-sdk.git`
2. Setup a local PostgreSQL server
3. Copy the `.env.example` file to `.env` and fill in the database credentials
4. Add the AI LLM provider keys to `.env` with `OPENAI_API_KEY` and `GEMINI_API_KEY`
5. Run database migrations with `php artisan migrate`
6. Run database seed with `php artisan db:seed`
7. Serve the website locally by running `php artisan serve`

## Prompt AI Agent

The AI Agent can be interacted with through the various API endpoints defined in the Swagger documentation.

- L5 Swagger UI: Laravel AI SDK - API Documentation: [http://localhost:8000/api/documentation/](http://localhost:8000/api/documentation/)

### API Reference

Description | Endpoint
--- | ---
Health check | `/health-check`
Verify AI SDK | `/verify-ai-sdk`
Generate bearer token | `/generate-token`
Verify generated bearer token | `/me`
Start conversation with an AI agent | `/conversations/start`
Continue conversation with the AI agent | `/conversations/continue`
Prompt AI agent to analyse user sentiment | `/analysis/sentiment`
Simple anonymous agent | `/anonymous/simple`
Structured anonymous agent | `/anonymous/structured`
Creative configured agent | `/configured/creative`
Precise configured agent | `/configured/precise`
Tool-assisted agent | `/tools/time-assistant`
Prompt AI agent to search the web | `/web/search`
Prompt AI agent to fetch web content | `/web/fetch`
Process document from file | `/files/process-document-file`
Process document from local storage | `/files/process-document-storage`
Process image from file | `/files/process-image-file`
Process image from URL | `/files/process-image-url`
Generate image | `/images/generate`
Generate audio (text-to-speech) | `/audio/generate`
Generate text (transcription) | `/audio/transcribe`
