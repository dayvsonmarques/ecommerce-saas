<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function correios()
    {
        $config = Setting::firstOrCreate(
            ['key' => 'correios'],
            ['value' => [
                'cep_origem' => config('services.correios.cep_origem'),
                'codigo_empresa' => config('services.correios.codigo_empresa'),
                'senha' => config('services.correios.senha'),
            ]]
        );

        return Inertia::render('Admin/Settings/Correios', [
            'config' => $config->value,
        ]);
    }

    public function updateCorreios(Request $request)
    {
        $data = $request->validate([
            'cep_origem' => 'required|string',
            'codigo_empresa' => 'nullable|string',
            'senha' => 'nullable|string',
        ]);

        $setting = Setting::firstOrCreate(['key' => 'correios']);
        $setting->value = $data;
        $setting->save();

        return redirect()->route('admin.settings.correios')->with('success', 'Configurações dos Correios atualizadas.');
    }

    public function mercadopago()
    {
        $config = Setting::firstOrCreate(
            ['key' => 'mercadopago'],
            ['value' => [
                'public_key' => config('services.mercadopago.public_key'),
                'access_token' => config('services.mercadopago.access_token'),
                'integrator_id' => config('services.mercadopago.integrator_id'),
                'sandbox' => (bool) config('services.mercadopago.sandbox', true),
            ]]
        );

        return Inertia::render('Admin/Settings/MercadoPago', [
            'config' => $config->value,
        ]);
    }

    public function updateMercadoPago(Request $request)
    {
        $data = $request->validate([
            'public_key' => 'required|string',
            'access_token' => 'required|string',
            'integrator_id' => 'nullable|string',
            'sandbox' => 'required|boolean',
        ]);

        $setting = Setting::firstOrCreate(['key' => 'mercadopago']);
        $setting->value = $data;
        $setting->save();

        return redirect()->route('admin.settings.mercadopago')->with('success', 'Configurações do Mercado Pago atualizadas.');
    }
}
