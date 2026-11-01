<?php

namespace App\Models;

use App\Models\Concerns\HasToolExecutionLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityScanner extends Model
{
    /** @use HasFactory<\Database\Factories\SecurityScannerFactory> */
    use HasFactory;
    use HasToolExecutionLogs;

    protected $fillable = ['name'];
}
