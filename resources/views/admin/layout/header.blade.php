<header class="bg-white shadow-lg p-5 flex justify-between items-center relative z-0">
    <button id="sidebar-toggle" class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md p-2 transition-colors duration-200">
        <i class="fas fa-bars text-xl"></i>
    </button>
    <div class="text-3xl font-extrabold text-gray-900">
        داشبورد <span class="text-blue-600">اصلی</span>
    </div>
    <div class="flex items-center space-x-4">
        <div class="relative">
            <input type="text" placeholder="جستجو..." class="pr-10 pl-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 text-sm">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
        <button class="text-gray-600 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-2 transition-colors duration-200">
            <i class="fas fa-bell text-xl"></i>
        </button>
        <div class="relative">
            <div id="user-menu-button" class="flex items-center group cursor-pointer">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=150&q=80" alt="پروفایل" class="rounded-full h-10 w-10 border-2 border-transparent group-hover:border-blue-500 transition-all duration-200 object-cover">
                <div class="ml-3 text-gray-700 font-medium group-hover:text-blue-600 transition-colors duration-200 hidden md:block">
                    مدیر سیستم
                </div>
                <i class="fas fa-chevron-down text-sm ml-2 text-gray-500 group-hover:text-blue-600 transition-colors duration-200"></i>
            </div>

            <div id="user-menu" class="absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-lg py-2 z-50 ring-1 ring-black ring-opacity-5 hidden transition-all duration-200 ease-in-out">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-user ml-2"></i> پروفایل
                </a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-cog ml-2"></i> تنظیمات
                </a>
                <div class="border-t border-gray-100 my-1"></div>
                <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-sign-out-alt ml-2"></i> خروج
                </a>
            </div>
        </div>
    </div>
</header>
