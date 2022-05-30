class UserController
{
    private UserService $userService;
     
    public function __construct(UserService $userService)
    {
        $this->$userService = $userService;
    }
    
    public function store(StoreUserRequest $request)
    {
        // 1. Validation
        /* $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]); 
     
        // 2. Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // 3. Upload the avatar file and update the user
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('avatars');
            $user->update(['avatar' => $avatar]);
        } */
    
    
        // this violates the single responsibility principle
        // $user = $userService->createUser($request);
    
        // avatar upload is separated from the user creation operation
        $avatar = $userService->uploadAvatar($request);
    
        // we may call it from any Artisan command or elsewhere
        $user = $userService->createUser($request->validated() + ['avatar' => $avatar]);
     
        // 4. Login
        Auth::login($user);
     
        // 5. Generate a personal voucher
        // Since one of the features of Services is to conain multiple methods,
        /* $voucher = Voucher::create([
            'code' => Str::random(8),
            'discount_percent' => 10,
            'user_id' => $user->id
        ]); */
     
    
	// 6. Send that voucher with a welcome email
        // $user->notify(new NewUserWelcomeNotification($voucher->code));
    
        $userService->sendWelcomeEmail($user);
     
        // 7. Notify administrators about the new user
        // It may take time, so need to put it into queue to run in the background, we need Jobs
        /* foreach (config('app.admin_emails') as $adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new NewUserAdminNotification($user));
        } */
        
	NewUserNotifyAdminsJob::dispatch($user);


	// Method2, Using event
	// Replace passive code with event and listener
	NewUserRegistered::dispatch($user);

	// Method3, Using Observer 
     
        return redirect()->route('dashboard');
    }
}
    
