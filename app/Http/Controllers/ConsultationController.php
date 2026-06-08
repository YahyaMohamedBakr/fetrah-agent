<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultationController extends Controller
{
    public function index()
    {
        return view('consultations.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'specialty' => 'required|string|max:255',
            'message' => 'nullable|string|max:2000',
            'preferred_date' => 'nullable|date',
            'time_slot' => 'nullable|string|max:50',
        ]);

        Consultation::create($validated);

        return back()->with('success', 'تم إرسال طلب الاستشارة بنجاح. سنتواصل معك قريباً.');
    }
}
