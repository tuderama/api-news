<?php

namespace App\Http\Controllers;

use App\Models\NewsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function getNews()
    {
        try {
            $news = NewsModel::with('user')->get();
            if ($news) {
                return response()->json([
                    'status' => 200,
                    'data' => $news
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No news found", 404
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function showNews(NewsModel $news)
    {
        try {
            if ($news) {
                $showNews = NewsModel::where('id', $news->id)->with('user')->get();
                return response()->json([
                    'status' => 200,
                    'data' => $showNews
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "News not found"
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function createNews(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'title' => 'required|string',
                'content' => 'required|string',
                'image' => 'required|image',
                'desc' => 'required|string',
            ]);
            if ($validasi->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validasi->errors()
                ], 400);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/news', $imageName);

            $news = NewsModel::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $imageName,
                'desc' => $request->desc,
                'slug' => Str::of($request->title)->slug('-'),
                'user_id' => auth()->user()->id
            ]);

            return response()->json([
                'status' => 201,
                'message' => 'News created successfully',
                'data' => $news
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateNews(Request $request, NewsModel $news)
    {
        try {
            if (!$news) {
                return response()->json([
                    'status' => 404,
                    'message' => 'News not found',
                ], 404);
            }

            $validasi = Validator::make($request->all(), [
                'title' => 'string',
                'content' => 'string',
                'image' => 'image',
                'desc' => 'string',
            ]);

            if ($validasi->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Validation failed',
                    'errors' => $validasi->errors()
                ], 400);
            }

            if ($request->hasFile('image')) {
                if ($news->image) {
                    Storage::delete('public/news/' . $news->image);
                }
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/news', $imageName);

                $news->update([
                    'title' => $request->title,
                    'content' => $request->content,
                    'image' => $imageName,
                    'desc' => $request->desc,
                    'slug' => Str::of($request->title)->slug('-'),
                    'user_id' => auth()->user()->id
                ]);
            } else {
                $news->update([
                    'title' => $request->title,
                    'content' => $request->content,
                    'desc' => $request->desc,
                    'slug' => Str::of($request->title)->slug('-'),
                    'user_id' => auth()->user()->id
                ]);
            }

            return response()->json([
                'status' => 201,
                'message' => 'News update successfully',
                'data' => $news
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteNews(NewsModel $news)
    {
        try {
            if (!$news) {
                return response()->json([
                    'status' => 404,
                    'message' => 'News not found',
                ], 404);
            }
            $news->delete();
            return response()->json([
                'status' => 200,
                'message' => 'News deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
