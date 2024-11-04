<x-mail::message>
# Welcome to List Ninja! 🥷

Hi {{ $user->username }},

Thanks for joining List Ninja! We're excited to have you on board.

Here's what you can do to get started:
- Create your first list
- Follow other ninjas
- Earn achievements

<x-mail::button :url="route('dashboard')">
Visit Dashboard
</x-mail::button>

Remember, every ninja journey begins with a single list!

Thanks,<br>
The List Ninja Team
</x-mail::message>