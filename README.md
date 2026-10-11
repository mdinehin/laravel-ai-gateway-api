# Laravel AI SDK

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
2. Setup a local PostgreSQL database called `laravel_ai_sdk`
3. Copy the `.env.example` file to `.env` and fill in the database credentials
4. Add the AI LLM provider keys to `.env` with `OPENAI_API_KEY` and `GEMINI_API_KEY`
5. Run database migrations with `php artisan migrate`
6. Run database seed with `php artisan db:seed`
7. Serve the website locally by running `php artisan serve`

## Prompt AI Agent

The AI can be accessed using cURL or through the Postman API platform.

Generate the Bearer token string:
```
curl --location 'http://localhost:8000/api/path1/generate-token' \
--form 'email="demouser@example.com"' \
--form 'password="password"'
```

Prompt the AI agent:
```
curl --location 'http://localhost:8000/api/path2/prompt' \
--header 'Authorization: Bearer [TOKEN_STRING]' \
--form 'prompt="Which AI model are you?"'
```
