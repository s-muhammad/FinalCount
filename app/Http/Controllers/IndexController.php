<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

class IndexController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('welcome', compact('categories'));
    }

    // متد نمایش اخبار یک دسته بندی
    public function category($id)
    {
        $category = Category::where('id', $id)->firstOrFail();
        $blogs = Blog::where('category_id', $category->id)->latest()->paginate(12);
        $pageTitle = $category->getTranslation('name', app()->getLocale());
        $pageDescription = $category->getTranslation('description', app()->getLocale());
        $pageImage = $category->image;

        return view('list', compact('blogs', 'pageTitle', 'pageDescription', 'pageImage'));
    }

// متد نمایش اخبار یک تگ
    public function tag($slug)
    {
        // ۱. جستجوی تگ در تمام کلیدهای زبانی (چون ممکن است کاربر روی تگ Fallback کلیک کرده باشد)
        $tag = \Spatie\Tags\Tag::where('slug->fa', $slug)
            ->orWhere('slug->en', $slug)
            ->orWhere('slug->ar', $slug)
            ->firstOrFail();

        // ۲. دریافت اخبار مرتبط با این تگ
        $blogs = Blog::withAnyTags([$tag])->latest()->paginate(12);

        // ۳. ارسال عنوان اختصاصی این تگ (با استفاده از تایپ خود تگ )
        $pageTitle = $tag->getTranslation('name', $tag->type);
        $pageDescription = __('tag_page_description');

        return view('list', compact('blogs', 'pageTitle', 'pageDescription'));
    }

    public function single(Blog $blog)
    {
        $captcha = $this->generateMathCaptcha();
        session([
            'comment_captcha_answer' => $captcha['answer'],
            'comment_captcha_question' => $captcha['question']
        ]);
        $comments = $blog->comments()->where('approved', 1)->get();
        return view('single',[
            'blog' => $blog,
            'comments' => $comments,
            'captcha' => $captcha['question']
        ]);
    }

    public function storeComment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100',
            'comment' => 'required|string|min:3|max:1000',
            'blog_id' => 'required|exists:blogs,id',
            'captcha_answer' => 'required|numeric'
        ]);

        $captchaAnswer = session('comment_captcha_answer');
        $userAnswer = (int) $request->input('captcha_answer');

        if ($userAnswer !== $captchaAnswer) {
            return back()
                ->withErrors(['captcha_answer' => 'پاسخ کپچا نادرست است!'])
                ->withInput();
        }

        Comment::create([
            'name' => $request->name,
            'comment' => $request->comment,
            'email' => $request->email,
            'blog_id' => $request->blog_id,
        ]);

        // پاک کردن کپچا
        session()->forget(['comment_captcha_answer', 'comment_captcha_question']);

        return back()->with('success', 'دیدگاه شما با موفقیت ثبت شد!');
    }
    private function generateMathCaptcha()
    {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);

        $operators = ['+', '-', '*'];
        $operator = $operators[array_rand($operators)];

        switch ($operator) {
            case '+':
                $answer = $num1 + $num2;
                break;
            case '-':
                $answer = $num1 - $num2;
                break;
            case '*':
                $answer = $num1 * $num2;
                break;
            default:
                $answer = $num1 + $num2;
        }

        return [
            'question' => "$num1 $operator $num2 = ?",
            'answer' => $answer
        ];
    }
}
