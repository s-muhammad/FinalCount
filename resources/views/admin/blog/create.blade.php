@extends('admin.layout.app')

@section('main')
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-10">

        {{-- عنوان صفحه --}}
        <div class="flex items-center mb-8 justify-between border-b pb-4">
            <h2 class="text-3xl font-extrabold text-gray-800">
                <i class="fas fa-pen-nib text-blue-600 ml-3"></i>
                ایجاد مقاله جدید
            </h2>
        </div>

        {{-- اعلان خطاها --}}
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

        {{-- کانتینر اصلی فرم --}}
        <div class="bg-white rounded-2xl shadow-xl p-0 overflow-hidden">
            <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" class="relative">
                @csrf

                {{-- ۱. رادیوباتن‌های مخفی (کنترلر تب‌ها با تیلویند خالص در بالاترین سطح) --}}
                <input type="radio" name="lang_tabs" id="tab-fa" class="peer/fa hidden" checked>
                <input type="radio" name="lang_tabs" id="tab-ar" class="peer/ar hidden">
                <input type="radio" name="lang_tabs" id="tab-en" class="peer/en hidden">

                {{-- ۲. بخش ناوبری تب‌ها با استایل تزریقی به فرزندان --}}
                <div class="border-b border-gray-200 bg-gray-50/50
                            peer-checked/fa:[&_.label-fa]:bg-blue-600 peer-checked/fa:[&_.label-fa]:text-white peer-checked/fa:[&_.label-fa]:shadow-lg peer-checked/fa:[&_.label-fa]:translate-y-[1px]
                            peer-checked/ar:[&_.label-ar]:bg-purple-600 peer-checked/ar:[&_.label-ar]:text-white peer-checked/ar:[&_.label-ar]:shadow-lg peer-checked/ar:[&_.label-ar]:translate-y-[1px]
                            peer-checked/en:[&_.label-en]:bg-green-600 peer-checked/en:[&_.label-en]:text-white peer-checked/en:[&_.label-en]:shadow-lg peer-checked/en:[&_.label-en]:translate-y-[1px]">

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
                        <i class="fas fa-edit ml-2"></i> محتوای نسخه فارسی
                    </h4>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">عنوان مقاله</label>
                        <input type="text" name="title[fa]" value="{{ old('title.fa') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm focus:border-blue-500 focus:ring-4
                               focus:ring-blue-100 transition duration-300" placeholder="عنوان مقاله را وارد کنید">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">خلاصه مقاله</label>
                        <textarea name="summary[fa]" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm
                        focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition duration-300" placeholder="خلاصه کوتاه برای پیش‌نمایش..."
                        >{{ old('summary.fa') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">محتوای کامل</label>
                        <textarea name="body[fa]" rows="8" class="mytextarea w-full px-4 py-3 rounded-lg border border-gray-300 text-sm
                        focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition duration-300">{{ old('body.fa') }}</textarea>
                    </div>

                    <div class="select2-blue">
                        <label class="block text-gray-700 text-sm font-medium mb-2">برچسب‌ها (فارسی)</label>
                        <select name="tags[fa][]" multiple="multiple" id="tags-fa" class="w-full select2-input">
                            @foreach($tags->where('type', 'fa') as $tag)
                                <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="hidden peer-checked/ar:block p-8 space-y-6 animate-fade-in">
                    <h4 class="text-xl font-bold text-purple-700 mb-6 border-b pb-3">
                        <i class="fas fa-edit ml-2"></i> محتوى النسخة العربية
                    </h4>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">عنوان المقال</label>
                        <input type="text" name="title[ar]" value="{{ old('title.ar') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm focus:border-purple-500 focus:ring-4
                               focus:ring-purple-100 transition duration-300" placeholder="أدخل العنوان هنا">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">ملخص المقال</label>
                        <textarea name="summary[ar]" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm
                        focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition duration-300" placeholder="ملخص قصير..."
                        >{{ old('summary.ar') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">المحتوى الكامل</label>
                        <textarea name="body[ar]" rows="8" class="mytextarea w-full px-4 py-3 rounded-lg border border-gray-300 text-sm
                        focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition duration-300">{{ old('body.ar') }}</textarea>
                    </div>

                    <div class="select2-purple">
                        <label class="block text-gray-700 text-sm font-medium mb-2">الوسوم (العربية)</label>
                        <select name="tags[ar][]" multiple="multiple" id="tags-ar" class="w-full select2-input">
                            @foreach($tags->where('type', 'ar') as $tag)
                                <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="hidden peer-checked/en:block p-8 space-y-6 animate-fade-in" dir="ltr">
                    <h4 class="text-xl font-bold text-green-700 mb-6 border-b pb-3 flex items-center justify-start gap-2 text-left">
                        <i class="fas fa-edit"></i> English Version Content
                    </h4>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2 text-left">Article Title</label>
                        <input type="text" name="title[en]" value="{{ old('title.en') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm focus:border-green-500 focus:ring-4
                               focus:ring-green-100 transition duration-300 text-left" placeholder="Enter title">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2 text-left">Article Summary</label>
                        <textarea name="summary[en]" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm
                        focus:border-green-500 focus:ring-4 focus:ring-green-100 transition duration-300 text-left" placeholder="Short summary..."
                        >{{ old('summary.en') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2 text-left">Full Content</label>
                        <textarea name="body[en]" rows="8" class="mytextarea w-full px-4 py-3 rounded-lg border border-gray-300 text-sm
                        focus:border-green-500 focus:ring-4 focus:ring-green-100 transition duration-300 text-left">{{ old('body.en') }}</textarea>
                    </div>

                    <div class="select2-green text-left">
                        <label class="block text-gray-700 text-sm font-medium mb-2 text-left">Tags (English)</label>
                        <select name="tags[en][]" multiple="multiple" id="tags-en" class="w-full select2-input" dir="ltr">
                            @foreach($tags->where('type', 'en') as $tag)
                                <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="p-6 pt-2 space-y-6 animate-fade-in">
                    <label class="block text-gray-700 text-sm font-medium mb-2">دسته بندی</label>
                    <select name="category_id" class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm focus:border-blue-500 focus:ring-4
                               focus:ring-blue-100 transition duration-300">
                        <option>انتخاب کنید</option>
                        @foreach($categories as $cat)
                            <option value="{{$cat->id}}">{{$cat->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="p-6 space-y-6 animate-fade-in">
                    <div>
                        <label for="image" class="block text-gray-700 text-sm font-medium mb-3">تصویر مقاله</label>
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 border-2 border-dashed border-gray-300 shadow-sm">
                                <i class="fas fa-image text-xl"></i>
                            </div>
                            <input type="file" id="image" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer transition duration-300">
                        </div>
                    </div>
                </div>

                {{-- نوار اکشن پایینی دکمه‌ها --}}
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex justify-between items-center rounded-b-2xl">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-blue-500/30 transition duration-300 transform hover:scale-[1.01] flex items-center gap-2">
                        <i class="fas fa-save"></i> ذخیره مقاله
                    </button>

                    <a href="{{ route('admin.blog.index') }}" class="bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-bold py-3 px-8 rounded-xl shadow-sm transition duration-300 flex items-center gap-2">
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // اضافه کردن width: '100%' به تمام سابمیت‌ها
            $("#tags-fa").select2({
                tags: true,
                dir: "rtl",
                width: '100%' // <-- این خط مشکل رو کاملا حل می‌کنه
            });

            $("#tags-ar").select2({
                tags: true,
                dir: "rtl",
                width: '100%' // <-- این خط مشکل رو کاملا حل می‌کنه
            });

            $("#tags-en").select2({
                tags: true,
                dir: "ltr",
                width: '100%' // <-- این خط مشکل رو کاملا حل می‌کنه
            });
        });
    </script>
@endsection
