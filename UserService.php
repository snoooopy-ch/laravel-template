namespace App\Services;
 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
 
class UserService
{
    public function uploadAvatar(Request $request): ?string
    {
        return ($request->hasFile('avatar'))
            ? $request->file('avatar')->store('avatars')
            : NULL;
    }
 
    public function createUser(array $userData): User
    {
        return User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'avatar' => $userData['avatar']
        ]);
    }

    public function createVoucherForUser(int $userId): string
    {
        $voucher = Voucher::create([
            'code' => Str::random(8),
            'discount_percent' => 10,
            'user_id' => $userId
        ]);
 
        return $voucher->code;
    }

    public function sendWelcomeEmail(User $user)
    {
        $voucherCode = $this->createVoucherForUser($user->id);
        $user->notify(new NewUserWelcomeNotification($voucherCode));
    }
}