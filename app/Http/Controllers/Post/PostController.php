<?php

namespace App\Http\Controllers\Post;

use Exception;
use Validator;
use App\Models\Response;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use App\Models\Post\PostAction;
use App\Http\Controllers\Controller;

 /**
  * @version 1.0.0
  */
class PostController extends Controller
{
    /**
     * Store a newly created post in database.
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return Response::generate('error', $validator->errors()->getMessages(), 422);
            }

            $post = Post::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return Response::generate('success', $post, 201);
        } catch (Exception $e) {
            return Response::generate('error', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified post.
     *
     * @since 1.0.0
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try {
            $post = Post::findOrFail($id);

            return Response::generate('success', $post, 200);
        } catch (Exception $e) {
            return Response::generate('error', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified post from database.
     *
     * @since 1.0.0
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $post = Post::findOrFail($id)->delete();

            return Response::generate('success', $post, 204);
        } catch (Exception $e) {
            return Response::generate('error', $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified post in database.
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        Request $request,
        int $id
    ) {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return Response::generate('error', $validator->errors()->getMessages(), 422);
            }

            $post = Post::where('id', $id)->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return Response::generate('success', $post, 200);
        } catch (Exception $e) {
            return Response::generate('error', $e->getMessage(), 500);
        }
    }

    /**
     * Display a listing of the post based on parameters.
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $result = (new PostAction)->init()
                ->search($request->search)
                ->sort($request->field, $request->value)
                ->filter($request->filter)
                ->paginate($request->page)
                ->execute();

            return Response::generate('success', $result, 200);
        } catch (Exception $e) {
            return Response::generate('error', $e->getMessage(), 500);
        }
    }
}
