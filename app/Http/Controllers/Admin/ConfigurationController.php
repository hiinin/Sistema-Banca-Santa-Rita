<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateConfigurationRequest;
use App\Models\Configuration;
use App\Services\MediaUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConfigurationController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected MediaUploadService $mediaService
    ) {}

    /**
     * Display the settings page.
     */
    public function index(): View
    {
        $config = Configuration::current();

        return view('admin.configurations.index', compact('config'));
    }

    /**
     * Update the settings in storage.
     */
    public function update(UpdateConfigurationRequest $request): RedirectResponse
    {
        $config = Configuration::current();
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $this->mediaService->delete($config->logo);
            $data['logo'] = $this->mediaService->uploadImage($request->file('logo'), 'configurations');
        }

        if ($request->hasFile('favicon')) {
            $this->mediaService->delete($config->favicon);
            $data['favicon'] = $this->mediaService->uploadImage($request->file('favicon'), 'configurations');
        }

        $config->update($data);

        return redirect()->route('admin.configurations.index')
            ->with('success', 'Configurações da Banca Santa Rita atualizadas com sucesso!');
    }
}
