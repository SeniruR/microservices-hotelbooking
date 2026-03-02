<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('booking-events:consume {--once : Consume a single batch and exit}', function () {
    $stream = (string) env('BOOKING_EVENTS_STREAM', 'booking-events');
    $group = (string) env('BOOKING_EVENTS_GROUP', 'notifications');
    $consumer = (string) env('BOOKING_EVENTS_CONSUMER', 'local-1');

    $client = Redis::connection()->client();

    try {
        if (method_exists($client, 'executeRaw')) {
            $client->executeRaw(['XGROUP', 'CREATE', $stream, $group, '$', 'MKSTREAM']);
        } else {
            Redis::command('xgroup', ['CREATE', $stream, $group, '$', 'MKSTREAM']);
        }
    } catch (Throwable) {
        // Group likely already exists.
    }

    $this->info("Consuming Redis stream '{$stream}' as group '{$group}' consumer '{$consumer}'...");

    $readId = '0';

    do {
        if (method_exists($client, 'executeRaw')) {
            $result = $client->executeRaw([
                'XREADGROUP',
                'GROUP', $group, $consumer,
                'COUNT', '10',
                'BLOCK', '5000',
                'STREAMS', $stream, $readId,
            ]);
        } else {
            $result = Redis::command('xreadgroup', [
                'GROUP', $group, $consumer,
                'COUNT', '10',
                'BLOCK', '5000',
                'STREAMS', $stream, $readId,
            ]);
        }

        $readId = '>';

        if (!$result) {
            if ($this->option('once')) {
                return 0;
            }

            continue;
        }

        $streams = $result;
        if (is_array($result) && array_is_list($result) && isset($result[0]) && is_array($result[0]) && count($result[0]) === 2) {
            $streams = [];
            foreach ($result as $item) {
                $streams[$item[0]] = $item[1];
            }
        }

        foreach ($streams as $streamName => $messages) {
            foreach ($messages as $message) {
                $id = $message[0] ?? null;
                $fields = $message[1] ?? [];

                if (!is_array($fields) && is_array($message) && count($message) > 2) {
                    $fields = array_slice($message, 1);
                }

                if (!is_array($fields)) {
                    $fields = [];
                }

                $payload = [];
                for ($i = 0; $i < count($fields); $i += 2) {
                    $key = $fields[$i] ?? null;
                    $value = $fields[$i + 1] ?? null;
                    if ($key !== null) {
                        $payload[$key] = $value;
                    }
                }

                $type = $payload['type'] ?? 'Unknown';
                $this->line("Event {$type} ({$id})");
                Log::info('booking-event', ['id' => $id, 'type' => $type, 'payload' => $payload]);

                if ($id) {
                    if (method_exists($client, 'executeRaw')) {
                        $client->executeRaw(['XACK', $streamName, $group, $id]);
                    } else {
                        Redis::command('xack', [$streamName, $group, $id]);
                    }
                }
            }
        }

        if ($this->option('once')) {
            return 0;
        }
    } while (true);
})->purpose('Consume booking events from Redis Streams');
