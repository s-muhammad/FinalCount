@extends('admin.layout.app')
@section('main')
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-10">
        <div class="flex items-center mb-8 justify-between border-b pb-4">
            <h2 class="text-3xl font-extrabold text-gray-800">
                <i class="fas fa-pen-nib text-blue-600 ml-3"></i>
                ویرایش دسته بندی
            </h2>
        </div>
        @if ($errors->any())
            <div class="bg-red-50 border-r-4 border-red-500 text-red-700 p-4 rounded-lg mb-8 shadow-md transition-all duration-300">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle text-xl ml-3"></i>
                    <p class="font-bold text-sm">لطفاً خطاهای زیر را برطرف کنید:</p>
                </div>
                <ul class="mt-1 list-disc list-inside text-sm pr-10 border-red-200">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl p-0 overflow-hidden">
            <form action="{{ route('admin.categories.update',$category->id) }}" method="POST" enctype="multipart/form-data" class="relative">
                @csrf
                @method('put')
                {{-- ۱. رادیوباتن‌های مخفی (کنترلر تب‌ها با تیلویند خالص در بالاترین سطح) --}}
                <input type="radio" name="lang_tabs" id="tab-fa" class="peer/fa hidden" checked>
                <input type="radio" name="lang_tabs" id="tab-ar" class="peer/ar hidden">
                <input type="radio" name="lang_tabs" id="tab-en" class="peer/en hidden">

                {{-- ۲. بخش ناوبری تب‌ها با استایل تزریقی به فرزندان --}}
                <div class="border-b border-gray-200 bg-gray-50/50
                            peer-checked/fa:[&_.label-fa]:bg-blue-600 peer-checked/fa:[&_.label-fa]:text-white peer-checked/fa:[&_.label-fa]:shadow-lg peer-checked/fa:[&_.label-fa]:translate-y-[1px]
                            peer-checked/ar:[&_.label-ar]:bg-purple-600 peer-checked/ar:[&_.label-ar]:text-white peer-checked/ar:[&_.label-ar]:shadow-lg peer-checked/ar:[&_.label-ar]:translate-y-[1px]
                            peer-checked/en:[&_.label-en]:bg-green-600 peer-checked/en:[&_.label-en]:text-white peer-checked/en:[&_.label-en]:shadow-lg peer-checked/en:[&_.label-en]:translate-y-[1px]"
                >

                    <nav class="flex space-x-2 space-x-reverse px-8 pt-4">
                        <label for="tab-fa" class="label-fa cursor-pointer px-5 py-3 font-semibold text-sm rounded-t-lg transition-all duration-300 flex items-center gap-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200">
                            <i class="fas fa-language"></i> 🇮🇷 فارسی (اصلی)
                        </label>

                        <label for="tab-ar" class="label-ar cursor-pointer px-5 py-3 font-semibold text-sm rounded-t-lg transition-all duration-300 flex items-center gap-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200">
                            <i class="fas fa-language"></i> 🇸🇦 العربية
                        </label>

                        <label for="tab-en" class="label-en cursor-pointer px-5 py-3 font-semibold text-sm rounded-t-lg transition-all duration-300 flex items-center gap-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200" dir="ltr">
                            🇬🇧 English <i class="fas fa-language"></i>
                        </label>
                    </nav>
                </div>


                <div class="hidden peer-checked/fa:block p-8 space-y-6 animate-fade-in">
                    <h4 class="text-xl font-bold text-blue-700 mb-6 border-b pb-3">
                        <i class="fas fa-edit ml-2"></i>  فارسی
                    </h4>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">عنوان </label>
                        <input type="text" name="name[fa]" value="{{ $category->getTranslation('name', 'fa') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm focus:border-blue-500
                                focus:ring-4 focus:ring-blue-100 transition duration-300" placeholder="عنوان مقاله را وارد کنید">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">توضیحات </label>
                        <textarea name="description[fa]" rows="8" class="mytextarea w-full px-4 py-3 rounded-lg border
                        border-gray-300 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition duration-300"
                        >{{ $category->getTranslation('description', 'fa') }}</textarea>
                    </div>
                </div>

                <div class="hidden peer-checked/ar:block p-8 space-y-6 animate-fade-in">
                    <h4 class="text-xl font-bold text-purple-700 mb-6 border-b pb-3">
                        <i class="fas fa-edit ml-2"></i> العربية
                    </h4>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">عنوان </label>
                        <input type="text" name="name[ar]" value="{{ $category->getTranslation('name', 'ar') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm focus:border-purple-500
                               focus:ring-4 focus:ring-purple-100 transition duration-300" placeholder="أدخل العنوان هنا">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">التوضیحات </label>
                        <textarea name="description[ar]" rows="8" class="mytextarea w-full px-4 py-3 rounded-lg border
                        border-gray-300 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition duration-300"
                        >{{ $category->getTranslation('description', 'ar') }}</textarea>
                    </div>
                </div>

                <div class="hidden peer-checked/en:block p-8 space-y-6 animate-fade-in" dir="ltr">
                    <h4 class="text-xl font-bold text-green-700 mb-6 border-b pb-3 flex items-center justify-start gap-2 text-left">
                        <i class="fas fa-edit"></i> English
                    </h4>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2 text-left"> Title</label>
                        <input type="text" name="name[en]" value="{{ $category->getTranslation('name', 'en') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm focus:border-green-500
                               focus:ring-4 focus:ring-green-100 transition duration-300 text-left" placeholder="Enter title">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2 text-left"> Description</label>
                        <textarea name="description[en]" rows="8" class="mytextarea w-full px-4 py-3 rounded-lg border
                        border-gray-300 text-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition duration-300 text-left"
                        >{{ $category->getTranslation('description', 'en') }}</textarea>
                    </div>
                </div>

                <div class="p-8 border-t border-gray-200 bg-gray-50/30">
                    <h4 class="text-lg font-bold text-gray-700 mb-6">
                        <i class="fas fa-cog ml-2 text-gray-500"></i> تنظیمات
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- کارت آپلود تصویر --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition duration-300 flex flex-col justify-center">
                            <label for="image" class="block text-gray-800 text-sm font-bold mb-4">تصویر شاخص دسته بندی</label>
                            <div class="flex items-center space-x-4 ">
                                <div class="w-34 h-24 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500 border border-blue-100 shadow-sm shrink-0">
                                    <img src="{{asset($category->image)}}" alt="">
                                </div>
                                <input type="file" id="image" name="image" class="w-full text-xs text-gray-500 file:mr-0 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer transition duration-300">
                            </div>
                        </div>

                        {{-- کارت تاگل: نمایش در منو --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition duration-300">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-gray-800 text-sm font-bold">نمایش در منوی اصلی</label>
                                <i class="fas fa-list text-gray-400"></i>
                            </div>
                            <p class="text-xs text-gray-500 mb-5 leading-relaxed">با فعال‌سازی این گزینه، دسته‌بندی در نوار بالای سایت برای کاربران نمایش داده می‌شود.</p>

                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_in_menu" value="0">
                                <input type="checkbox" name="is_in_menu" value="1" class="sr-only peer"
                                    {{ old('is_in_menu' , $category->is_in_menu) == '1' ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:-translate-x-7 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="mr-3 text-sm font-bold text-gray-500 peer-checked:text-blue-600 transition-colors">فعال</span>
                            </label>
                        </div>

                        {{-- کارت تاگل: نمایش در صفحه اصلی --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition duration-300">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-gray-800 text-sm font-bold">نمایش در صفحه اصلی</label>
                                <i class="fas fa-home text-gray-400"></i>
                            </div>
                            <p class="text-xs text-gray-500 mb-5 leading-relaxed">با فعال‌سازی این گزینه، بلوک اخبار این دسته در صفحه نخست سایت (Landing) بارگذاری می‌شود.</p>

                            <label class="relative inline-flex items-center cursor-pointer">
                                {{-- فیلد مخفی برای ارسال مقدار 0 در صورت خاموش بودن تاگل --}}
                                <input type="hidden" name="is_on_homepage" value="0">
                                <input type="checkbox" name="is_on_homepage" value="1" class="sr-only peer"
                                    {{ old('is_on_homepage', $category->is_on_homepage) == '1' ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-100 rounded-full peer peer-checked:after:-translate-x-7 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-purple-600"></div>
                                <span class="mr-3 text-sm font-bold text-gray-500 peer-checked:text-purple-600 transition-colors">فعال</span>
                            </label>
                        </div>

                    </div>
                </div>


                {{-- نوار اکشن پایینی دکمه‌ها --}}
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex justify-between items-center rounded-b-2xl">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-blue-500/30 transition duration-300 transform hover:scale-[1.01] flex items-center gap-2">
                        <i class="fas fa-save"></i> ذخیره
                    </button>

                    <a href="{{ route('admin.categories.index') }}" class="bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-bold py-3 px-8 rounded-xl shadow-sm transition duration-300 flex items-center gap-2">
                        <i class="fas fa-times"></i> انصراف
                    </a>
                </div>

            </form>
        </div>
    </main>

    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.35rem !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: transparent !important;
        }
        .select2-blue .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
        }
        .select2-purple .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #a855f7 !important;
            box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.1) !important;
        }
        .select2-green .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1) !important;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
@endsection
