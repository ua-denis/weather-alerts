<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * @return View
     */
    public function show(): View
    {
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $validatedData = $request->validate($this->rules());
        
        $user = Auth::user();
        if (!$user) {
            return back()->withErrors(['profile' => 'User is not authenticated.']);
        }

        $this->updateProfile($user, $validatedData);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * @return array
     */
    private function rules(): array
    {
        return [
            'precipitation' => 'required|numeric|min:0',
            'uv_index' => 'required|numeric|min:0',
            'cities' => 'array',
            'cities.*' => 'string|max:255',
        ];
    }

    /**
     * @param  User  $user
     * @param  array  $data
     * @return void
     */
    private function updateProfile(Authenticatable $user, array $data): void
    {
        $profile = $user->profile ?? $user->profile()->create();
        $notificationThresholds = json_encode($data['precipitation'], $data['uv_index']);

        $profile->update([
            'notification_thresholds' => $notificationThresholds,
            'cities' => json_encode($data['cities'] ?? []),
        ]);
    }
}
