class NewUserNotifyAdminsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 
    private User $user;
 
    public function __construct(User $user)
    {
        $this->user = $user;
    }
 
    public function handle()
    {
        foreach (config('app.admin_emails') as $adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new NewUserAdminNotification($this->user));
        }
    }
}