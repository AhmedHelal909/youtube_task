<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FetchJob extends Model
{
    protected $fillable = [
        'categories',
        'status',
        'total_saved',
        'total_skipped',
        'total_errors',
        'log',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'categories'  => 'array',
        'log'         => 'array',
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function appendLog(string $message): void
    {
        $lines   = $this->log ?? [];
        $lines[] = '[' . now()->format('H:i:s') . '] ' . $message;
        $this->log = $lines;
        $this->save();
    }

    public function markRunning(): void
    {
        $this->update([
            'status'     => 'running',
            'started_at' => now(),
        ]);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status'      => 'completed',
            'finished_at' => now(),
        ]);
    }

    public function markFailed(string $reason): void
    {
        $this->appendLog("ERROR: {$reason}");
        $this->update([
            'status'      => 'failed',
            'finished_at' => now(),
        ]);
    }
}
