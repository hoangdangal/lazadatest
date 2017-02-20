 <?php





use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use App\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * list all post with GET request
 */
Route::get('/post',function (){
	if(Cache::has('list_post'))
	{
		$posts = Cache::get('list_post');		
	}
	else 
	{
		$posts = App\Post::all(['id','title','body','create_date']);
		Cache::put('list_post',$posts);		
	}
	return response()->json(['code' => 1,'posts' => $posts]);
});

/**
 * create post with PUT request
 */
Route::put('/post',function (Request $request){
	// check require
	if($request->input('title') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter title']);	
	if($request->input('body') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter body']);
		
	// insert database
	$post = new App\Post();
	$post->title = $request->input('title');
	$post->body = $request->input('body');
	$rs = $post->save();
	
	// send mail
	if($rs)
	{
		Illuminate\Support\Facades\Mail::to(env('MAIL_TO'))->send(new App\Mail\PostCreatedMail($post->id));
	}
	
	// set cache
	$posts = App\Post::all(['id','title','body','create_date']);
	Cache::put('list_post',$posts);
	
	return response()->json(['code' => $rs ? 1:0 ]);
});

/**
 * update post with POST request
 */
Route::post('/post',function (Request $request){
	// check require
	if($request->input('id') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter id']);
	if($request->input('title') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter title']);	
	if($request->input('body') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter body']);

	$post = App\Post::find($request->input('id'));
	if($post == null)		
		return response()->json(['code' => 2,'message' => 'Post is not exist']);
		
	// update to database		
	$post->title = $request->input('title');
	$post->body = $request->input('body');
	$post->update_date = date('Y-m-d h:i:s');
	$rs = $post->save();
	
	// set cache
	$posts = App\Post::all(['id','title','body','create_date']);
	Cache::put('list_post',$posts);
	
	return response()->json(['code' => $rs ? 1:0]);
});

/**
 * delete post with DELETE request
 */
Route::delete('/post',function (Request $request){
	// check require
	if($request->input('id') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter id']);
	
	$post = App\Post::find($request->input('id'));
	if($post == null)		
		return response()->json(['code' => 2,'message' => 'Post is not exist']);
	
	// update database
	$rs = $post->delete();
	// save log
	if($rs)
	{
		$log = new App\Log();
		$log->method = 'DELETE';
		$log->data_json = json_encode(['id'=>$post->id,'title'=>$post->title,'body'=>$post->body,'create_date'=>$post->create_date]);
		$log->save();
		
		// set cache
		$posts = App\Post::all(['id','title','body','create_date']);
		Cache::put('list_post',$posts);
	}
	return response()->json(['code' => $rs ? 1:0]);
});

/*********************************************************************/

/**
 * list all tag with GET request
 */
Route::get('/tag',function (){
	if(Cache::has('list_tag'))
	{
		$tags = Cache::get('list_tag');
	}
	else
	{
		$tags = DB::select('select name from tags');
		Cache::put('list_tag',$tags);
	}
	
	return response()->json(['code' => 1,'tags' => $tags]);
});


/**
 * create tag with PUT request
 */
Route::put('/tag',function (Request $request){
	// check require
	if($request->input('name') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter name']);	

	$tag = App\Tags::find(str_replace(' ', '', $request->input('name')));
	if($tag != null)
		return response()->json(['code' => 2,'message' => 'Tag name is exist']);
		
	// insert database
	$tag = new App\Tags();
	$tag->name = str_replace(' ', '', $request->input('name'));	
	$rs = $tag->save();
		
	if($rs)
	{
		// set cache
		$tags = DB::select('select name from tags');
		Cache::put('list_tag',$tags);
	}
	
	return response()->json(['code' => $rs ? 1:0]);
});

/**
 * update tag with POST request
 */
Route::post('/tag',function (Request $request){
	// check require
	if($request->input('name') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter name']);
	
	if($request->input('newName') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter newName']);
		
	$tag = App\Tags::find(str_replace(' ', '', $request->input('name')));
	if($tag == null)
		return response()->json(['code' => 2,'message' => 'Tag is not exist']);

	// update tag
	$tag->name = $tag->name = str_replace(' ', '', $request->input('newName'));	
	$rs = $tag->save();
	
	// update tag on post if exist
	DB::select('update tag_on_post set tag_name = ? where tag_name = ?',[$request->input('newName'),$request->input('name')]);
	
	// set cache
	$tags = DB::select('select name from tags');
	Cache::put('list_tag',$tags);
	
	return response()->json(['code' => $rs ? 1:0]);
});

/**
 * delete tag with DELETE request
 */
Route::delete('/tag',function (Request $request){
	// check require
	if($request->input('name') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter name']);

	$tag = App\Tags::find($request->input('name'));
	if($tag == null)
		return response()->json(['code' => 2,'message' => 'Tag is not exist']);

	// update database
	$rs = $tag->delete();
	
	// set cache
	if($rs)
	{
		$tags = DB::select('select name from tags');
		Cache::put('list_tag',$tags);
	}
	
	return response()->json(['code' => $rs ? 1:0]);
});

/***** additional method *********/

/**
 * tag to post with POST request
 */
Route::post('/tagpost',function (Request $request){
	// check require
	if($request->input('tagName') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter tagName']);

	if($request->input('postId') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter postId']);
	
	$tags = DB::select('select name from tags where name = ?',[$request->input('tagName')]);
	if(sizeof($tags) == 0)
		return response()->json(['code' => 2,'message' => 'Tag is not exist']);
			
	$tagOnPost = new App\Tag_on_post();
	$tagOnPost->tag_name = $tags[0]->name;
	$tagOnPost->post_id = $request->input('postId');
	$rs = $tagOnPost->save();
	return response()->json(['code' => 1 , 'data' => $rs ? 1:0]);
});

/**
 * show posts by tag or tags with POST request
 */
Route::post('/showpost',function (Request $request){
	// check require
	if($request->input('tags') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter tags.Tags value type : tag1,tag2']);
		
	$tags = $request->input('tags');
	$tagsList = explode(',', $tags);
	for ($i = 0;$i < sizeof($tagsList);$i++)
	{
		$tagsList[$i]= "'".$tagsList[$i]."'";
	}
	$tags = implode(',', $tagsList);
	
	$rs = DB::select("select p.id as post_id,p.title,p.body,p.create_date,t.name from posts p 
						inner join tag_on_post t_on_p on t_on_p.post_id = p.id
						inner join tags t on t.name = t_on_p.tag_name
						where t.name in (".$tags.")
			");		
	return response()->json(['code' => 1 , 'data' => $rs]);
});

/**
 * count all posts by tag or tags with POST request
 */
Route::post('/postcount',function (Request $request){
	// check require
	if($request->input('tags') == '')
		return response()->json(['code' => 2,'message' => 'Missing parameter tags.Tags value type : tag1,tag2']);

	$tags = $request->input('tags');
	$tagsList = explode(',', $tags);
	for ($i = 0;$i < sizeof($tagsList);$i++)
	{
		$tagsList[$i]= "'".$tagsList[$i]."'";
	}
	$tags = implode(',', $tagsList);

		$rs = DB::select("select count(p.id) as post_count 
					from posts p
					inner join tag_on_post t_on_p on t_on_p.post_id = p.id
					inner join tags t on t.name = t_on_p.tag_name
					where t.name in (".$tags.")
		");
		return response()->json(['code' => 1 , 'data' => $rs]);
});



	