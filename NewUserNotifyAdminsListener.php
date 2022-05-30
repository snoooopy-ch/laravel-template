use App\Events\NewUserRegistered;
use App\Notifications\NewUserAdminNotification;
use Illuminate\Support\Facades\Notification;
 
class NewUserNotifyAdminsListener
{
    public function handle(NewUserRegistered $event)
    {
        foreach (config('app.admin_emails') as $adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new NewUserAdminNotification($event->user));
        }
    }
}