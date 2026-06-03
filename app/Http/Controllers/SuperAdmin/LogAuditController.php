<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogAuditController extends Controller
{
    public function viewLogAudit()
    {
        return view('pages.superadmin.log-audit');
    }
}
