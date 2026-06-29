@extends('layout.app')
@section('main')
    <main class="max-w-7xl mx-auto px-4 py-10 w-full flex-grow">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-8">
                <article class="glass-panel rounded-3xl overflow-hidden shadow-2xl">
                    <div class="w-full h-[300px] md:h-[450px] relative overflow-hidden">
                        <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover opacity-80">
                        <div class="absolute inset-0 bg-gradient-to-t from-[var(--img-fade)] via-transparent to-transparent"></div>
                        <div class="absolute top-4 right-4">
                            @php
                                $currentLocale = app()->getLocale();
                                $localeTags = $blog->tags->where('type', $currentLocale);
                                if($localeTags->isEmpty()) {
                                    $localeTags = $blog->tags->where('type', 'fa');
                                }
                            @endphp
                            <div class="flex flex-wrap gap-2 mt-4">
                                @foreach($localeTags->take(2) as $tag)
                                    <span class="bg-yellow-600/90 text-black text-xs font-bold px-4 py-1.5 rounded-full backdrop-blur-sm
                                shadow-sm transition hover:bg-yellow-500 cursor-pointer">
                                    {{ $tag->getTranslation('name', $tag->type) }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="p-6 md:p-10">
                        <div class="flex flex-wrap items-center gap-4 text-xs text-[var(--text-faint)] mb-6 border-b border-[var(--border-base)] pb-6">
                            <div class="flex items-center gap-1"><span>📅</span><span>{{ $blog->created_at->diffForHumans() }}</span></div>
                            <span class="text-[var(--border-strong)]">•</span>
                            <div class="flex items-center gap-1"><span>✍️</span><span>{{ __('editorial_board') }}</span></div>
                            <span class="text-[var(--border-strong)]">•</span>
                            <div class="flex items-center gap-1"><span>⏱️</span><span>{{ __('reading_time') }} {{ $blog->reading_time }} {{ __('minutes') }}</span></div>
                        </div>

                        <h1 class="text-2xl md:text-4xl font-black leading-tight mb-8 text-[var(--text-primary)]">
                            {{ $blog->title }}
                        </h1>

                        <div class="article-content">
                            <p>
                                {!! $blog->body !!}
                            </p>
                        </div>
                    </div>
                </article>

                <div class="glass-panel rounded-3xl p-6 md:p-10 shadow-2xl">
                    <h3 class="text-xl font-black text-[var(--text-primary)] mb-8 flex items-center gap-2">
                        <span class="text-yellow-500">💬</span>
                        <span>{{ __('submit_comment_title') }}</span>
                    </h3>
                    <form action="{{ route('comment.store') }}" method="post" class="space-y-4">
                        @csrf
                        <input type="hidden" name="blog_id" value="{{$blog->id}}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-[var(--text-muted)] mb-2 mr-1">{{ __('your_name') }}</label>
                                <input type="text" class="w-full bg-[var(--bg-input)] border border-[var(--border-input)] rounded-xl px-4 py-3 text-sm
                                text-[var(--text-primary)] focus:outline-none focus:border-yellow-500/50 transition" name="name" value="{{ old('name') }}">
                            </div>
                            <div>
                                <label class="block text-xs text-[var(--text-muted)] mb-2 mr-1">{{ __('email_address') }}</label>
                                <input type="email" class="w-full bg-[var(--bg-input)] border border-[var(--border-input)] rounded-xl px-4 py-3 text-sm
                                text-[var(--text-primary)] text-left focus:outline-none focus:border-yellow-500/50 transition" name="email" value="{{ old('email') }}" dir="ltr">
                            </div>
                        </div>

                        {{-- بخش اصلاح شده کپچا --}}
                        <div>
                            <label class="block text-xs text-[var(--text-muted)] mb-2 mr-1">{{ __('security_question') }}</label>
                            <div class="flex items-center gap-3">
                                <div class="w-1/3 md:w-1/4 bg-[var(--display-bg)] border border-dashed border-yellow-600/40 rounded-xl py-3 text-center
                                text-lg font-mono font-bold text-yellow-500 shadow-inner" dir="ltr">
                                    {{ session('comment_captcha_question') }}
                                </div>

                                <div class="w-2/3 md:w-3/4">
                                    <input type="number"
                                           name="captcha_answer"
                                           class="w-full bg-[var(--bg-input)] border border-[var(--border-input)] rounded-xl px-4 py-3 text-sm text-[var(--text-primary)]
                                           font-mono focus:outline-none focus:border-yellow-500/50 transition text-center placeholder-[var(--text-faint)]"
                                           placeholder="{{ __('enter_answer_placeholder') }}"
                                           value="{{ old('captcha_answer') }}"
                                           required>
                                </div>
                            </div>

                            @error('captcha_answer')
                            <p class="text-red-400 text-xs mt-2 mr-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs text-[var(--text-muted)] mb-2 mr-1">{{ __('message_body') }}</label>
                            <textarea rows="4" class="w-full bg-[var(--bg-input)] border border-[var(--border-input)] rounded-xl px-4 py-3 text-sm text-[var(--text-primary)]
                            focus:outline-none focus:border-yellow-500/50 transition resize-none" name="comment">{{ old('comment') }}</textarea>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="bg-gradient-to-r from-yellow-600 to-yellow-500 text-black font-black text-xs
                            md:text-sm px-6 py-3 rounded-xl shadow-lg transition hover:scale-[1.02]">
                                {{ __('submit_comment_button') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="glass-panel rounded-3xl p-6 md:p-10 shadow-2xl space-y-6">
                    <h3 class="text-xl font-black text-[var(--text-primary)] mb-6 flex items-center gap-2">
                        <span class="text-yellow-500">👥</span>
                        <span>{{ __('comments_list_title') }} ({{ $blog->comments->count() }})</span>
                    </h3>

                    <div class="space-y-4">
                        @forelse($comments as $comment)
                            <div class="bg-[var(--bg-input)] border border-[var(--border-base)] rounded-2xl p-4 md:p-6 space-y-3">
                                <div class="flex items-center justify-between border-b border-[var(--border-base)] pb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-yellow-500/10 border border-yellow-500/30 flex items-center justify-center text-yellow-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-[var(--text-primary)]">{{ $comment->name ?? __('anonymous_user') }}</h4>
                                            <span class="text-[10px] text-[var(--text-faint)] font-mono">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-[var(--text-secondary)] text-sm leading-relaxed whitespace-pre-line">
                                    {{ $comment->comment }}
                                </p>
                            </div>
                        @empty
                            <div class="text-center py-8 border border-dashed border-[var(--border-input)] rounded-2xl bg-[var(--bg-input)]">
                                <span class="text-3xl block mb-2">📥</span>
                                <p class="text-[var(--text-muted)] text-sm">{{ __('no_comments_yet') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <aside class="lg:col-span-4 space-y-6 sticky top-24">

                <div class="glass-panel rounded-3xl p-2 relative group overflow-hidden border border-yellow-600/30 shadow-[0_0_20px_rgba(234,179,8,0.05)]">
                    <div class="absolute top-4 right-4 z-10">
                        <span class="bg-black/80 backdrop-blur border border-white/10 text-gray-400 text-[10px] px-2 py-1 rounded">{{ __('ads_support') }}</span>
                    </div>
                    <div class="w-full h-48 bg-gray-900 rounded-2xl overflow-hidden relative">
                        <img src="https://images.pexels.com/photos/3183150/pexels-photo-3183150.jpeg?auto=compress&cs=tinysrgb&w=600" alt="بنر تبلیغاتی" class="w-full h-full object-cover opacity-50 group-hover:opacity-80 group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-4">
                            <h4 class="text-yellow-400 font-bold text-sm mb-1">{{ __('support_media_front') }}</h4>
                            <p class="text-xs text-gray-300">{{ __('support_media_front_desc') }}</p>
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-3xl p-6">
                    <h3 class="text-sm font-bold text-[var(--text-secondary)] mb-5 flex items-center gap-2 border-b border-[var(--border-base)] pb-3">
                        <span class="text-yellow-500">📰</span>
                        <span>{{ __('related_posts') }}</span>
                    </h3>

                    <div class="space-y-4">
                        @foreach(\App\Models\Blog::where('category_id',$blog->category_id)->take(3)->get() as $post)
                            <a href="{{ route('single',$post->id) }}" class="group flex items-center gap-3 p-2 -mx-2 rounded-xl hover:bg-[var(--hover-wash)] transition">
                                <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="w-16 h-16 object-cover rounded-lg opacity-70 group-hover:opacity-100 transition">
                                <div class="space-y-1">
                                    <h4 class="text-xs font-bold text-[var(--text-secondary)] group-hover:text-yellow-400 transition line-clamp-2">{{ $post->title }}</h4>
                                    <span class="text-[10px] text-[var(--text-faint)]">{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="glass-panel rounded-3xl p-6">
                    <h3 class="text-sm font-bold text-[var(--text-secondary)] mb-5 flex items-center gap-2 border-b border-[var(--border-base)] pb-3">
                        <span class="text-yellow-500">🏷️</span>
                        <span>{{ __('hot_tags') }}</span>
                    </h3>

                    <div class="flex flex-wrap gap-2">
                        @foreach($localeTags->take(6) as $tag)
                            <a href="{{ route('tag.list', $tag->getTranslation('slug', $tag->type)) }}"
                               class="text-[11px] bg-[var(--bg-input)] border border-[var(--border-base)] hover:border-yellow-500/50 hover:bg-yellow-500/10 text-[var(--text-muted)] hover:text-yellow-400 px-3 py-1.5 rounded-lg transition duration-300">
                                #{{ $tag->getTranslation('name', $tag->type) }}
                            </a>
                        @endforeach
                    </div>
                </div>

            </aside>
        </div>
    </main>
@endsection
