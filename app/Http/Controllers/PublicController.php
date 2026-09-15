<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Message;
use App\Models\Media;
use App\Models\Article;
use App\Models\Interview;
use App\Models\Quote;
use App\Models\Gallery;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PublicController extends Controller
{
    public function isModuleActive($module)
    {
        return Setting::getValue("module_active_{$module}", '1') === '1';
    }

    public function index()
    {
        $latestNews = $this->isModuleActive('news')
            ? News::where('status', 'published')->latest()->take(6)->get()
            : collect();
        $featuredNews = $this->isModuleActive('news')
            ? News::where('status', 'published')->where('is_featured', true)->latest()->first()
            : null;
        $latestMessages = $this->isModuleActive('messages')
            ? Message::where('status', 'published')->latest()->take(3)->get()
            : collect();
        $latestSpeech = $this->isModuleActive('messages')
            ? Message::where('status', 'published')->where('type', 'speech')->latest()->first()
            : null;
        $latestMedia = $this->isModuleActive('media')
            ? Media::where('status', 'published')->latest()->take(6)->get()
            : collect();
        $quotes = Quote::where('is_active', true)->inRandomOrder()->take(3)->get();
        $gallery = $this->isModuleActive('gallery')
            ? Gallery::where('status', 'published')->latest()->take(8)->get()
            : collect();

        $activeModules = [
            'news' => $this->isModuleActive('news'),
            'messages' => $this->isModuleActive('messages'),
            'media' => $this->isModuleActive('media'),
            'articles' => $this->isModuleActive('articles'),
            'quotes' => $this->isModuleActive('quotes'),
            'gallery' => $this->isModuleActive('gallery'),
            'contact' => $this->isModuleActive('contact'),
        ];

        return view('welcome', compact('latestNews', 'featuredNews', 'latestMessages', 'latestSpeech', 'latestMedia', 'quotes', 'gallery', 'activeModules'));
    }

    public function setLocale($locale)
    {
        if (in_array($locale, ['fa', 'ar', 'en'])) {
            session(['locale' => $locale]);
            App::setLocale($locale);
        }
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $q = $request->input('q', '');
        $results = collect();

        if (strlen($q) >= 2) {
            if ($this->isModuleActive('news')) {
                $results = $results->merge(
                    News::where('status', 'published')
                        ->where(function ($query) use ($q) {
                            $query->where('title', 'like', "%{$q}%")
                                ->orWhere('title_ar', 'like', "%{$q}%")
                                ->orWhere('title_en', 'like', "%{$q}%")
                                ->orWhere('summary', 'like', "%{$q}%")
                                ->orWhere('body', 'like', "%{$q}%");
                        })->latest()->take(20)->get()->map(fn($item) => ['type' => 'news', 'item' => $item])
                );
            }

            if ($this->isModuleActive('messages')) {
                $results = $results->merge(
                    Message::where('status', 'published')
                        ->where(function ($query) use ($q) {
                            $query->where('title', 'like', "%{$q}%")
                                ->orWhere('title_ar', 'like', "%{$q}%")
                                ->orWhere('title_en', 'like', "%{$q}%")
                                ->orWhere('summary', 'like', "%{$q}%")
                                ->orWhere('body', 'like', "%{$q}%");
                        })->latest()->take(20)->get()->map(fn($item) => ['type' => 'message', 'item' => $item])
                );
            }

            if ($this->isModuleActive('media')) {
                $results = $results->merge(
                    Media::where('status', 'published')
                        ->where(function ($query) use ($q) {
                            $query->where('title', 'like', "%{$q}%")
                                ->orWhere('title_ar', 'like', "%{$q}%")
                                ->orWhere('title_en', 'like', "%{$q}%")
                                ->orWhere('description', 'like', "%{$q}%");
                        })->latest()->take(20)->get()->map(fn($item) => ['type' => 'media', 'item' => $item])
                );
            }

            if ($this->isModuleActive('articles')) {
                $results = $results->merge(
                    Article::where('status', 'published')
                        ->where(function ($query) use ($q) {
                            $query->where('title', 'like', "%{$q}%")
                                ->orWhere('title_ar', 'like', "%{$q}%")
                                ->orWhere('title_en', 'like', "%{$q}%")
                                ->orWhere('summary', 'like', "%{$q}%")
                                ->orWhere('body', 'like', "%{$q}%");
                        })->latest()->take(20)->get()->map(fn($item) => ['type' => 'article', 'item' => $item])
                );
            }
        }

        return view('public.search', compact('q', 'results'));
    }

    public function news()
    {
        if (!$this->isModuleActive('news')) abort(404);
        $news = News::where('status', 'published')->latest()->paginate(12);
        return view('public.news', compact('news'));
    }

    public function newsShow(News $news)
    {
        if (!$this->isModuleActive('news')) abort(404);
        $related = News::where('status', 'published')->where('id', '!=', $news->id)->latest()->take(4)->get();
        return view('public.news-show', compact('news', 'related'));
    }

    public function messages()
    {
        if (!$this->isModuleActive('messages')) abort(404);
        $messages = Message::where('status', 'published')->latest()->paginate(12);
        return view('public.messages', compact('messages'));
    }

    public function messageShow(Message $message)
    {
        if (!$this->isModuleActive('messages')) abort(404);
        $related = Message::where('status', 'published')->where('id', '!=', $message->id)->latest()->take(4)->get();
        return view('public.message-show', compact('message', 'related'));
    }

    public function media()
    {
        if (!$this->isModuleActive('media')) abort(404);
        $media = Media::where('status', 'published')->latest()->paginate(12);
        return view('public.media', compact('media'));
    }

    public function mediaShow(Media $media)
    {
        if (!$this->isModuleActive('media')) abort(404);
        return view('public.media-show', compact('media'));
    }

    public function culture()
    {
        if (!$this->isModuleActive('articles')) abort(404);
        $articles = Article::where('status', 'published')->latest()->paginate(12);
        return view('public.culture', compact('articles'));
    }

    public function contact()
    {
        if (!$this->isModuleActive('contact')) abort(404);
        return view('public.contact');
    }

    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        return redirect()->route('public.contact')->with('success', __('messages.message_sent'));
    }
}
