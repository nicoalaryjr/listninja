<div x-data="{ 
    open: false,
    notifications: [],
    unreadCount: {{ auth()->user()->unreadNotifications->count() }},
    
    init() {
        // Load initial notifications
        this.notifications = @json(auth()->user()->notifications()->latest()->take(10)->get());
        
        // Listen for new notifications
        window.Echo.private('App.Models.User.{{ auth()->id() }}')
            .notification((notification) => {
                this.notifications.unshift(notification);
                this.unreadCount++;
                this.showNotificationAlert(notification);
            });
    },

    showNotificationAlert(notification) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-white rounded-lg shadow-lg p-4 max-w-sm w-full transform transition-transform duration-300 ease-in-out z-50';
        
        let content = '';
        if (notification.type === 'like') {
            content = `<strong>${notification.username}</strong> liked your list "${notification.list_title}"`;
        } else if (notification.type === 'comment') {
            content = `<strong>${notification.username}</strong> commented on your list "${notification.list_title}"`;
        } else if (notification.type === 'follow') {
            content = `<strong>${notification.username}</strong> started following you`;
        }
        
        toast.innerHTML = `
            <div class="flex items-start">
                <div class="flex-1">
                    <p class="text-sm text-gray-900">${content}</p>
                    <p class="mt-1 text-xs text-gray-500">${notification.created_at}</p>
                </div>
                <button class="ml-4 text-gray-400 hover:text-gray-500" onclick="this.parentElement.parentElement.remove()">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
}" 
class="relative">
    <!-- Notification Bell Button -->
    <button @click="open = !open" 
            class="relative text-white hover:text-accent focus:outline-none">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>
        
        <!-- Unread Badge -->
        <template x-if="unreadCount > 0">
            <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 text-xs flex items-center justify-center" 
                  x-text="unreadCount">
            </span>
        </template>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-1 z-50">
        
        <div class="max-h-96 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-3 text-sm text-gray-500 text-center">
                    No notifications
                </div>
            </template>

            <template x-for="notification in notifications" :key="notification.id">
                <div class="px-4 py-3 hover:bg-gray-50" 
                     :class="{ 'opacity-75': notification.read_at }">
                    <template x-if="notification.data.type === 'like'">
                        <a :href="`/lists/${notification.data.list_id}`" class="block">
                            <p class="text-sm text-gray-900">
                                <span class="font-medium" x-text="notification.data.username"></span>
                                liked your list "
                                <span x-text="notification.data.list_title"></span>
                                "
                            </p>
                            <p class="mt-1 text-xs text-gray-500" x-text="notification.created_at"></p>
                        </a>
                    </template>

                    <template x-if="notification.data.type === 'comment'">
                        <a :href="`/lists/${notification.data.list_id}`" class="block">
                            <p class="text-sm text-gray-900">
                                <span class="font-medium" x-text="notification.data.username"></span>
                                commented on your list "
                                <span x-text="notification.data.list_title"></span>
                                "
                            </p>
                            <p class="text-xs text-gray-600 mt-1" x-text="notification.data.comment"></p>
                            <p class="mt-1 text-xs text-gray-500" x-text="notification.created_at"></p>
                        </a>
                    </template>

                    <template x-if="notification.data.type === 'follow'">
                        <a :href="`/profile/${notification.data.user_id}`" class="block">
                            <p class="text-sm text-gray-900">
                                <span class="font-medium" x-text="notification.data.username"></span>
                                started following you
                            </p>
                            <p class="mt-1 text-xs text-gray-500" x-text="notification.created_at"></p>
                        </a>
                    </template>
                </div>
            </template>
        </div>

        <!-- Mark all as read button -->
        <template x-if="unreadCount > 0">
            <div class="border-t border-gray-100 px-4 py-2">
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="text-sm text-accent hover:text-accent/80 w-full text-center"
                            @click="unreadCount = 0">
                        Mark all as read
                    </button>
                </form>
            </div>
        </template>
    </div>
</div>