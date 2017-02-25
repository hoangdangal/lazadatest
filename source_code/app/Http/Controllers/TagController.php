<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use \Illuminate\Support\Facades\DB;


class TagController extends Controller
{
    //
	public function __construct()
	{
		 
	}
	
	public function ListTag()
	{
		if (\Illuminate\Support\Facades\Cache::has ( 'list_tag' ))
			$tags = Cache::get ( 'list_tag' );
		else
		{
			$tags = \App\Tag::all();
			\Illuminate\Support\Facades\Cache::put ( 'list_tag', $tags );
		}
		
		return response ()->json(['code' => 1,'tags' => $tags->toArray()]);
	}
	
	public function CreateTag(Request $request)
	{
		// check require
		if ($request->input ( 'name' ) == '')
			return response ()->json ( ['code' => 2,'message' => 'Missing parameter name'] );
				
		$tag = \App\Tag::find(str_replace(' ', '', $request->input('name')));
		if ($tag != null)
			return response ()->json ( ['code' => 2,'message' => 'Tag name is exist' ]);
		
		// insert database
		$tag = new \App\Tag ();
		$tag->name = str_replace ( ' ', '', $request->input ( 'name' ) );
		$rs = $tag->save ();

		if ($rs) 
		{
			// set cache
			$tags = \App\Tag::all();
			\Illuminate\Support\Facades\Cache::put ( 'list_tag', $tags );
		}
		
		return response ()->json ( ['code' => $rs ? 1 : 0	]);
	}
	
	public function UpdateTag(Request $request)
	{
		// check require
		if ($request->input ( 'name' ) == '')
			return response ()->json (['code' => 2,'message' => 'Missing parameter name']);
		
		if ($request->input ( 'newName' ) == '')
			return response ()->json ( ['code' => 2,'message' => 'Missing parameter newName'] );

 		$tag = \App\Tag::find ( str_replace ( ' ', '', $request->input ( 'name' ) ) );
		if ($tag == null)
			return response ()->json ( ['code' => 2,'message' => 'Tag is not exist'] );
		
		// update tag
		$tag->name = $request->input ( 'newName' );
		$rs = $tag->save ();
		
		// update tag on post if exist
		\Illuminate\Support\Facades\DB::select ( 'update tag_on_post set tag_name = ? where tag_name = ?', [
				$request->input ( 'newName' ),
				$request->input ( 'name' )
		] );
		
		// set cache
		$tags = \App\Tag::all();
		\Illuminate\Support\Facades\Cache::put ( 'list_tag', $tags );
		
		return response ()->json ( ['code' => $rs ? 1 : 0] );
	}
	
	public function DeleteTag(Request $request)
	{
		// check require
		if ($request->input ( 'name' ) == '')
			return response ()->json ( [
					'code' => 2,
					'message' => 'Missing parameter name'
			] );
		
		$tag = \App\Tag::find ( $request->input ( 'name' ) );
		if ($tag == null)
			return response ()->json ( [
					'code' => 2,
					'message' => 'Tag is not exist'
			] );
	
		// update database
		$rs = $tag->delete ();
		// update tag on post if exist
		\Illuminate\Support\Facades\DB::select ( 'delete from tag_on_post where tag_name = ?', [
				$request->input ( 'name' )
		] );

		// set cache
		if ($rs) {
			$tags = \App\Tag::all();
			\Illuminate\Support\Facades\Cache::put ( 'list_tag', $tags );
		}
		
		return response ()->json ( [
				'code' => $rs ? 1 : 0
		] );
	}
	
	public function TagOnPost(Request $request)
	{
		// check require
		if ($request->input ( 'tagName' ) == '')
			return response ()->json ( [
					'code' => 2,
					'message' => 'Missing parameter tagName'
			] );
		
		if ($request->input ( 'postId' ) == '')
			return response ()->json ( [
					'code' => 2,
					'message' => 'Missing parameter postId'
			] );
		
		$tag =	\App\Tag::find ( $request->input ( 'tagName' ) );
		if($tag == null)
			return response ()->json ( [
						'code' => 2,
						'message' => 'Tag is not exist'
				] );

		$tagOnPost = new \App\Tag_on_post ();
		$tagOnPost->tag_name = $tag->name;
		$tagOnPost->post_id = $request->input ( 'postId' );
		$rs = $tagOnPost->save ();
		return response ()->json ( [
				'code' => 1
		] );
	}
	
	public function ShowPost(Request $request)
	{
		// check require
		if ($request->input ( 'tags' ) == '')
			return response ()->json ( [
					'code' => 2,
					'message' => 'Missing parameter tags.Tags value type : tag1,tag2'
			] );
		
		$tags = $request->input ( 'tags' );
		$tagsList = explode ( ',', $tags );
		for($i = 0; $i < sizeof ( $tagsList ); $i ++) {
			$tagsList [$i] = "'" . $tagsList [$i] . "'";
		}
		$tags = implode ( ',', $tagsList );
		
		$rs =  DB::select ( "select p.id as post_id,p.title,p.body,p.created_at,t.name as tag_name from posts p
				inner join tag_on_post t_on_p on t_on_p.post_id = p.id
				inner join tags t on t.name = t_on_p.tag_name
				where t.name in (" . $tags . ")
		" );
		return response ()->json ( [
				'code' => 1,
				'data' => $rs
		] );
	}
	
	public function CountPostByTag(Request $request)
	{
		// check require
		if ($request->input ( 'tags' ) == '')
			return response ()->json ( [
					'code' => 2,
					'message' => 'Missing parameter tags.Tags value type : tag1,tag2'
			] );
		
			$tags = $request->input ( 'tags' );
			$tagsList = explode ( ',', $tags );
			for($i = 0; $i < sizeof ( $tagsList ); $i ++) {
				$tagsList [$i] = "'" . $tagsList [$i] . "'";
			}
			$tags = implode ( ',', $tagsList );
		
			$rs = DB::select ( "select count(p.id) as post_count
					from posts p
					inner join tag_on_post t_on_p on t_on_p.post_id = p.id
					inner join tags t on t.name = t_on_p.tag_name
					where t.name in (" . $tags . ")
			" );
			
			return response ()->json ( [
					'code' => 1,
					'data' => $rs
			] );
	}
}
