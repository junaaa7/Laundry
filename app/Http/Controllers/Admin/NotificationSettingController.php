<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class NotificationSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'telegram_bot_token' => Setting::get('telegram_bot_token'),
            'telegram_chat_id' => Setting::get('telegram_chat_id'),
            'whatsapp_api_key' => Setting::get('whatsapp_api_key'),
            'whatsapp_sender_number' => Setting::get('whatsapp_sender_number'),
            'email_host' => Setting::get('email_host'),
            'email_port' => Setting::get('email_port'),
            'email_username' => Setting::get('email_username'),
            'email_password' => Setting::get('email_password'),
            'notification_on_order' => Setting::get('notification_on_order', true),
            'notification_on_status_change' => Setting::get('notification_on_status_change', true),
            'notification_on_payment' => Setting::get('notification_on_payment', true),
            'notification_on_completion' => Setting::get('notification_on_completion', true),
        ];
        
        return view('admin.notifications.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'telegram_bot_token' => 'nullable|string',
            'telegram_chat_id' => 'nullable|string',
            'whatsapp_api_key' => 'nullable|string',
            'whatsapp_sender_number' => 'nullable|string',
            'email_host' => 'nullable|string',
            'email_port' => 'nullable|string',
            'email_username' => 'nullable|string',
            'email_password' => 'nullable|string',
            'notification_on_order' => 'boolean',
            'notification_on_status_change' => 'boolean',
            'notification_on_payment' => 'boolean',
            'notification_on_completion' => 'boolean',
        ]);

        foreach ($request->all() as $key => $value) {
            if ($key !== '_token' && $key !== '_method') {
                Setting::set($key, $value);
            }
        }

        // Update .env file for email settings
        $this->updateEnvFile([
            'MAIL_HOST' => $request->email_host,
            'MAIL_PORT' => $request->email_port,
            'MAIL_USERNAME' => $request->email_username,
            'MAIL_PASSWORD' => $request->email_password,
        ]);

        return redirect()->route('admin.notifications.settings')->with('success', 'Pengaturan notifikasi berhasil diupdate');
    }

    private function updateEnvFile($data)
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);
        
        foreach ($data as $key => $value) {
            if ($value !== null) {
                $pattern = "/^{$key}=.*/m";
                $replacement = "{$key}={$value}";
                if (preg_match($pattern, $envContent)) {
                    $envContent = preg_replace($pattern, $replacement, $envContent);
                } else {
                    $envContent .= "\n{$replacement}";
                }
            }
        }
        
        file_put_contents($envPath, $envContent);
    }
}