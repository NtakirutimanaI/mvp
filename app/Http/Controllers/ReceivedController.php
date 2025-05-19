<?php

namespace App\Http\Controllers;

use App\Models\Received;
use Illuminate\Http\Request;

class ReceivedController extends Controller
{
    /**
     * Display a listing of the received reports.
     */
    public function index()
    {
        // Eager load related user and institution
        $receiveds = Received::with(['user', 'institution'])->paginate(10);
        return view('institution.received.index', compact('receiveds'));
    }
}
