use App\Events\NewUserRegistered;
use App\Services\UserService;
 
class NewUserWelcomeEmailListener
{
    public function handle(NewUserRegistered $event, UserService $userService)
    {
        $userService->sendWelcomeEmail($event->user);
    }
}