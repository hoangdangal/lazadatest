<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    //
    public function __construct()
    {
    	
    }
    
    public function ListPost()
    {
    	if (Cache::has ( 'list_post' )) {
			$posts = Cache::get ( 'list_post' );
		} else {
			$posts = \App\Post::all ( [ 
					'id',
					'title',
					'body',
					'created_at' 
			] );
			Cache::put ( 'list_post', $posts );
		}
		
    	$posts = App\Post::all ( ['id','title','body','created_at']);
		return response ()->json ([ 
				'code' => 1,
				'posts' => $posts 
		] );
    	
    }
    
    public function PutPost(Request $request)
    {
    	// check require
    	if ($request->input ( 'title' ) == '')
    		return response ()->json (['code' => 2,'message' => 'Missing parameter title']);
    	if ($request->input ( 'body' ) == '')
    		return response ()->json ( ['code' => 2,'message' => 'Missing parameter body']);
    	
 		// insert database
    	$post = new \App\Post ();
    	$post->title = $request->input ( 'title' );
    	$post->body = $request->input ( 'body' );
    	$rs = $post->save ();
    	
    	// send mail
    	if ($rs) {
    		\Illuminate\Support\Facades\Mail::to ( env ( 'MAIL_TO' ) )->send ( new App\Mail\PostCreatedMail ( $post->id ) );
    	}
    	
    	// set cache
    	$posts = \App\Post::all ( [
    					'id',
    					'title',
    					'body',
    					'created_at'
    			] );
    	Cache::put ( 'list_post', $posts );
    	
    	return response ()->json ( ['code' => $rs ? 1 : 0] );
    }

	public function UpdatePost(Request $request)
	{
		if ($request->input ( 'id' ) == '')
			return response ()->json ( ['code' => 2,'message' => 'Missing parameter id']);
		
		if ($request->input ( 'title' ) == '')
			return response ()->json ( ['code' => 2,'message' => 'Missing parameter title']);
		
		if ($request->input ( 'body' ) == '')
			return response ()->json ( ['code' => 2,'message' => 'Missing parameter body'] );
		
		$post = \App\Post::find ( $request->input ( 'id' ) );					
		if ($post == null)
			return response ()->json ( ['code' => 2,'message' => 'Post is not exist']);
							
		// update to database
		$post->title = $request->input ( 'title' );
		$post->body = $request->input ( 'body' );
		$rs = $post->save ();

		// set cache
		$posts = App\Post::all ( ['id','title','body','created_at']);
		Cache::put ( 'list_post', $posts );
		
		return response ()->json ( ['code' => $rs ? 1 : 0]);
	}

	public function DeletePost(Request $request)
	{
		// check require
		if ($request->input ( 'id' ) == '')
			return response ()->json ( ['code' => 2,'message' => 'Missing parameter id'] );
		
		$post = \App\Post::find ( $request->input ( 'id' ) );
		if ($post == null)
			return response ()->json ( ['code' => 2,'message' => 'Post is not exist'] );
		
		// update database
		$rs = $post->delete ();
		// save log
		if ($rs) 
		{
			$log = new \App\Log ();
			$log->method = 'DELETE';
			$log->data_json = json_encode(['id'=>$post->id,'title'=>$post->title,'body'=>$post->body,'created_at'=>$post->created_at]);
			$log->save ();
				
			// set cache
			$posts = \App\Post::all (['id','title','body','created_at']);
			Cache::put ( 'list_post', $posts );
		}
		
		return response ()->json (['code' => $rs ? 1 : 0]);
	}
}
