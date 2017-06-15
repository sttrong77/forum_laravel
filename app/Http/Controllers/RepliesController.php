<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;

class RepliesController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Persist a new reply.
     *
     * @param  integer $channelId
     * @param  Thread  $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), ['body' => 'required']);

        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        return back()->with('flash','Reply enviado com sucesso');
    }

    public function destroy(Reply $reply){

      $this->authorize('update', $reply);

      $reply->delete();

      if(request()->expectsJson()){
        return response(['status' => 'Reply deleted']);
      }

      return back();
    }

    public function update(Reply $reply){

      $this->authorize('update', $reply);

      $reply->update(request([
        'body'
      ]));
    }
}
