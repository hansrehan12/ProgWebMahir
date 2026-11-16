<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DosenRequest;
use App\Http\Resources\DosenResource;
use App\Models\Dosen;

class DosenApiController extends Controller
{
    public function index()
    {
        return DosenResource::collection(Dosen::paginate(10));
    }

    public function store(DosenRequest $request)
    {
        $dosen = Dosen::create($request->validated());

        return (new DosenResource($dosen))
            ->additional(['message' => 'Data dosen berhasil ditambahkan.'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Dosen $dosen)
    {
        return new DosenResource($dosen);
    }

    public function update(DosenRequest $request, Dosen $dosen)
    {
        $dosen->update($request->validated());

        return (new DosenResource($dosen))
            ->additional(['message' => 'Data dosen berhasil diperbarui.']);
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->delete();

        return response()->json(['message' => 'Data dosen berhasil dihapus.']);
    }
}