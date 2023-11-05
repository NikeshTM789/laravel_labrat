<?php

namespace App\Listeners\Admin;

use App\Events\Admin\UserCreateEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserCreateEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreateEvent $event): void
    {
        $request = $event->request;
        $role = $event->role;
        try {
            DB::transaction(function () use ($request, $role) {
                $password = Str::random(8);
                $token    = Str::random(64);
                $formData = $request->merge(['password' => $password])->only('name', 'email', 'password');

                Mail::send('mails.register_user', ['password' => $password, 'token' => $token], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('New User');
                });

                DB::table('password_resets')->insert([
                    'email'      => $request->email,
                    'token'      => $token,
                    'created_at' => now(),
                ]);

                $newly_user = User::create($formData)->assignRole($role);
                if ($request->hasFile('profile')) {
                    $newly_user->saveMedia();
                }
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
