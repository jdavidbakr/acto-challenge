<?php

namespace App\Http\Controllers;

use App\Http\Requests\HandRequest;
use App\Http\Resources\HandResource;
use App\Models\Hand;
use App\Services\HandPlayService;
use Illuminate\Http\Request;

class HandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HandRequest $request)
    {
        $hand = app(HandPlayService::class)->play($request->name, $request->cards);
        return new HandResource($hand);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hand  $hand
     * @return \Illuminate\Http\Response
     */
    public function show(Hand $hand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hand  $hand
     * @return \Illuminate\Http\Response
     */
    public function edit(Hand $hand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hand  $hand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hand $hand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hand  $hand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hand $hand)
    {
        //
    }
}
