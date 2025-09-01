<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:articles.view')->only(['index', 'show']);
        $this->middleware('permission:articles.create')->only(['create', 'store']);
        $this->middleware('permission:articles.edit')->only(['edit', 'update']);
        $this->middleware('permission:articles.delete')->only(['destroy']);
    }

    public function index()
    {
        $articles = Article::orderBy("created_at", "desc")->get();
        return view("articles/list", compact("articles"));
    }

    public function create()
    {
        return view("articles/create");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title"  => "unique:articles|required|min:10",
            "text"   => "max:1000|required",
            "author" => "required|min:3",
        ]);

        if ($validator->passes()) {
            Article::create($request->only('title', 'text', 'author'));
            return redirect()->route('articles.list')->with("success", "Article published successfully");
        }

        return redirect()->route('articles.create')->withErrors($validator)->withInput();
    }

    public function edit(string $id)
    {
        $articles = Article::findOrFail($id);
        return view('articles/edit', compact('articles'));
    }

    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "title"  => [
                "required",
                "min:10",
                Rule::unique("articles", "title")->ignore($id),
            ],
            "text"   => "required|max:1000",
            "author" => "required|min:3",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $article->update($request->only('title', 'text', 'author'));

        return redirect()->route('articles.list')->with("success", "Article updated successfully");
    }

    public function destroy(Request $request)
    {
        $article = Article::findOrFail($request->id);

        if ($article->delete()) {
            return response()->json(['message' => 'Article deleted successfully.', 'status' => true]);
        }

        return response()->json(['message' => 'Oops something went wrong!', 'status' => false]);
    }
}