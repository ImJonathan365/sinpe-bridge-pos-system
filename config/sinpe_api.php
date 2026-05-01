<?php

return [
	'base_url' => env('SINPE_API_BASE_URL', 'http://localhost:8000/api/v1'),
	'timeout' => (int) env('SINPE_API_TIMEOUT', 15),
	'retries' => (int) env('SINPE_API_RETRIES', 2),
	'retry_sleep_ms' => (int) env('SINPE_API_RETRY_SLEEP_MS', 300),
	'token' => env('SINPE_API_TOKEN'),
];
