<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[
    OA\Info(
        version: "1.0.0",
        title: "Laravel AI SDK - API Documentation",
        description: "This documentation provides a concise reference for configuring, calling, and extending AI-powered endpoints."
    ),
    OA\Server(url: "http://localhost:8000/api", description: "Local API Server #1"),
    OA\Server(url: "http://localhost:8001/api", description: "Local API Server #2"),
    OA\SecurityScheme(
        securityScheme: "bearerAuth",
        type: "http",
        name: "Authorization",
        description: "Enter JWT Bearer token.",
        in: "header",
        scheme: "bearer",
        bearerFormat: "JWT"
    ),
]
abstract class Controller
{
    //
}
