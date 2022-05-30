use App\Models\User;
 
class NewUserRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
 
    public User $user;
 
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}