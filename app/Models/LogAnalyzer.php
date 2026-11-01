<?php

namespace App\Models;

use App\Models\Concerns\HasToolExecutionLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAnalyzer extends Model
{
    /** @use HasFactory<\Database\Factories\LogAnalyzerFactory> */
    use HasFactory;
    use HasToolExecutionLogs;

    protected $fillable = ['name'];
}
