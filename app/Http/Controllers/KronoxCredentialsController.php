<?php

namespace App\Http\Controllers;

use App\KronoxCredentials;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class KronoxCredentialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $credentials = $request->user()->credentials;

        return view('credentials.index', [
            'credentials' => $credentials,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:kronox_credentials|min:3',
            'password' => 'required|:min:3',
        ]);

        $validated['password'] = Crypt::encrypt($validated['password']);

        /** @var KronoxCredentials $credential */
        $credential = $request->user()->credentials()->create($validated);

        $credential->poll();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param KronoxCredentials $kronoxCredentials
     * @return Response
     */
    public function show(KronoxCredentials $kronoxCredentials)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param KronoxCredentials $kronoxCredentials
     * @return Response
     */
    public function edit(KronoxCredentials $kronoxCredentials)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param KronoxCredentials $kronoxCredentials
     * @return Response
     */
    public function update(Request $request, KronoxCredentials $kronoxCredentials)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param KronoxCredentials $kronoxCredentials
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(KronoxCredentials $credential)
    {
        $credential->delete();

        return back();
    }
}
